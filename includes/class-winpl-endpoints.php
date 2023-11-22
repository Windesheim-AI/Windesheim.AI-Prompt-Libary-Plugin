<?php

class WinPL_Endpoints
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function register_endpoints()
    {
        $endpoint_prefix = 'winpl/v1';
        register_rest_route($endpoint_prefix, '/prompts', [
            'methods' => 'GET',
            'callback' => [$this, 'get_prompts'],
        ]);
        register_rest_route($endpoint_prefix, '/prompts/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_prompt'],
        ]);
        register_rest_route($endpoint_prefix, '/prompts', [
            'methods' => 'POST',
            'callback' => [$this, 'create_prompt'],
            'permission_callback' => function () {
                return is_user_logged_in() && current_user_can('edit_posts');
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
    //     "sector": "Education"
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


    public function create_prompt()
    {
        $decoded_request = json_decode(file_get_contents('php://input'));

        // Check if the request body is valid JSON
        if ($decoded_request == null) {
            return new WP_REST_Response('Invalid data given!', 400);
        }

        // Sanitize and validate input data
        $content = (object) [
            'title' => sanitize_text_field($decoded_request->title),
            'prompt' => sanitize_text_field($decoded_request->prompt),
            'description' => sanitize_text_field($decoded_request->description),
            'tool' => sanitize_text_field($decoded_request->tool),
            'toolLink' => esc_url_raw($decoded_request->toolLink),
            'promptPattern' => sanitize_text_field($decoded_request->promptPattern),
            'sector' => sanitize_text_field($decoded_request->sector),
        ];

        // Check if the prompt pattern and sector are valid
        global $wpdb;
        $prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
        $sector_table = $wpdb->prefix . 'winpl_sector';

        $prompt_pattern = $wpdb->get_var($wpdb->prepare("SELECT id FROM $prompt_pattern_table WHERE title = %s", $content->promptPattern));
        $sector = $wpdb->get_var($wpdb->prepare("SELECT id FROM $sector_table WHERE title = %s", $content->sector));

        if ($prompt_pattern == null || $sector == null) {
            return new WP_REST_Response('Invalid data given!', 400);
        }

        // Check if the prompt already exists
        $prompt_table = $wpdb->prefix . 'winpl_prompt';
        $prompt = $wpdb->get_row($wpdb->prepare("SELECT * FROM $prompt_table WHERE title = %s", $content->title));

        if ($prompt != null) {
            return new WP_REST_Response('Prompt already exists!', 400);
        }

        // Insert the prompt into the database
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
            ),
            array('%s', '%s', '%s', '%s', '%s', '%d', '%d')  // Adjust field types accordingly
        );

        // Return the prompt
        $prompt_id = (int) $wpdb->insert_id;
        $prompt = $wpdb->get_row($wpdb->prepare("SELECT * FROM $prompt_table WHERE id = %d", $prompt_id));
        $prompt->promptPattern = $wpdb->get_var($wpdb->prepare("SELECT title FROM $prompt_pattern_table WHERE id = %d", $prompt->promptPattern));
        $prompt->sector = $wpdb->get_var($wpdb->prepare("SELECT title FROM $sector_table WHERE id = %d", $prompt->sector));

        return $prompt;
    }
}
