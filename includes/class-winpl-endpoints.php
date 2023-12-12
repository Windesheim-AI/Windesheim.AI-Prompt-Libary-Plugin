<?php

class WinPL_Endpoints
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
        add_filter('rest_pre_serve_request', function ($served, $result, $request, $server) {
            header('X-Windesheim-Prompts-version: ' . WindesheimPromptLibary_API_VERSION);
            return $served;
        }, 10, 4);
    }

    public function register_endpoints()
    {
        $endpoint_prefix = 'winpl/v1';
        //prompts
        register_rest_route($endpoint_prefix, '/prompts', [
            'methods' => 'GET',
            'callback' => [$this, 'get_prompts'],
            'permission_callback' => function () {
                return is_user_logged_in();
            },
        ]);
        register_rest_route($endpoint_prefix, '/prompts/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_prompt'],
            'permission_callback' => function () {
                return is_user_logged_in();
            },
        ]);
        register_rest_route($endpoint_prefix, '/prompts', [
            'methods' => 'POST',
            'callback' => [$this, 'create_prompt'],
            'permission_callback' => function () {
                return is_user_logged_in() && current_user_can('edit_posts');
            },
        ]);

        //prompt patterns
        register_rest_route($endpoint_prefix, '/prompt-patterns', [
            'methods' => 'GET',
            'callback' => [$this, 'get_prompt_patterns'],
            'permission_callback' => function () {
                return is_user_logged_in();
            },
        ]);

        //sectors
        register_rest_route($endpoint_prefix, '/sectors', [
            'methods' => 'GET',
            'callback' => [$this, 'get_sectors'],
            'permission_callback' => function () {
                return is_user_logged_in();
            },
        ]);

    }

    // {
    //     "id": 123,
    //     "title": "Sample Prompt",
    //     "prompt": "Describe a historical event in detail",
    //     "description": "This prompt asks the user to describe a specific historical event, providing as much detail as possible.",
    //     "tool": "ChatGPT",
    //     "toolLink": "https://www.openai.com/chatgpt",
    //     "promptPattern": "PersonaPattern",
    //     "sector": "Education",
    //     "imageLink": "https://picsum.photos/200/300"
    // }

    public function get_prompts()
    {
        global $wpdb;
        $prompt_table = $wpdb->prefix . 'winpl_prompt';
        $prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
        $sector_table = $wpdb->prefix . 'winpl_sector';

        $prompts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $prompt_table"));

        foreach ($prompts as $prompt) {
            $prompt->promptPattern = $wpdb->get_var($wpdb->prepare("SELECT title FROM $prompt_pattern_table WHERE id = %d", $prompt->promptPattern));
            $prompt->sector = $wpdb->get_var($wpdb->prepare("SELECT title FROM $sector_table WHERE id = %d", $prompt->sector));
        }

        return $prompts;
    }

    public function get_prompt($request)
    {
        global $wpdb;
        $prompt_table = $wpdb->prefix . 'winpl_prompt';
        $prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
        $sector_table = $wpdb->prefix . 'winpl_sector';

        $prompt_id = intval($request['id']);  // Sanitize the input

        // Use prepared statement to prevent SQL injection
        $prompt = $wpdb->get_row($wpdb->prepare("SELECT * FROM $prompt_table WHERE id = %d", $prompt_id));

        if ($prompt == null) {
            return new WP_REST_Response('Prompt not found!', 404);
        }

        // Sanitize and retrieve related data
        $prompt->promptPattern = $wpdb->get_var($wpdb->prepare("SELECT title FROM $prompt_pattern_table WHERE id = %d", $prompt->promptPattern));
        $prompt->sector = $wpdb->get_var($wpdb->prepare("SELECT title FROM $sector_table WHERE id = %d", $prompt->sector));

        return $prompt;
    }


    public function create_prompt($request)
    {
        $request = $request->get_body();
        //check if the request body is valid JSON
        if ($request == null) {
            return new WP_REST_Response('Invalid data given!', 400);
        }
        //decode the request body
        $content = json_decode($request);
        //check if the request body contains all the required fields
        if (!isset($content->title) || !isset($content->prompt) || !isset($content->description) || !isset($content->tool) || !isset($content->toolLink) || !isset($content->promptPattern) || !isset($content->sector) || !isset($content->imageLink)) {
            return new WP_REST_Response('Invalid data given!', 400);
        }
        //check if the prompt pattern and sector are valid
        global $wpdb;
        $prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
        $sector_table = $wpdb->prefix . 'winpl_sector';
        $prompt_pattern = $wpdb->get_var("SELECT id FROM $prompt_pattern_table WHERE title = '$content->promptPattern'");
        $sector = $wpdb->get_var("SELECT id FROM $sector_table WHERE title = '$content->sector'");
        if ($prompt_pattern == null || $sector == null) {
            return new WP_REST_Response('Invalid data given!', 400);
        }
        //check if the prompt already exists
        $prompt_table = $wpdb->prefix . 'winpl_prompt';
        $prompt = $wpdb->get_row("SELECT * FROM $prompt_table WHERE title = '$content->title'");
        if ($prompt != null) {
            return new WP_REST_Response('Prompt already exists!', 400);
        }
        //insert the prompt into the database
        $wpdb->insert(
            $prompt_table,
            array(
                'title' => $content->title,
                'prompt' => $content->prompt,
                'description' => $content->description,
                'tool' => $content->tool,
                'toolLink' => $content->toolLink,
                'promptPattern' => $prompt_pattern,
                'sector' => $sector,
                'imageLink' => $content->imageLink,
            ),
            array('%s', '%s', '%s', '%s', '%s', '%d', '%d')  // Adjust field types accordingly
        );
        //return the new prompt
        $prompt_id = (int) $wpdb->insert_id;
        $prompt = $wpdb->get_row($wpdb->prepare("SELECT * FROM $prompt_table WHERE id = %d", $prompt_id));
        $prompt->promptPattern = $wpdb->get_var($wpdb->prepare("SELECT title FROM $prompt_pattern_table WHERE id = %d", $prompt->promptPattern));
        $prompt->sector = $wpdb->get_var($wpdb->prepare("SELECT title FROM $sector_table WHERE id = %d", $prompt->sector));
        return $prompt;
    }

    public function get_prompt_patterns()
    {
        global $wpdb;
        $prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
        $prompt_patterns = $wpdb->get_results("SELECT * FROM $prompt_pattern_table");
        return $prompt_patterns;
    }

    public function get_sectors()
    {
        global $wpdb;
        $sector_table = $wpdb->prefix . 'winpl_sector';
        $sectors = $wpdb->get_results("SELECT * FROM $sector_table");
        return $sectors;
    }
}
