<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!defined('INVALID_INPUT')) {
    define('INVALID_INPUT', -1);
}

function winpl_check_user()
{
    if (!is_user_logged_in()) {
        wp_die('You are not logged in!');
    }
    if (!current_user_can('manage_options')) {
        wp_die('You are not an admin!');
    }
}

function winpl_edit_prompt()
{
    winpl_check_user();

    $prompt_id = ($_POST['prompt_id'] ?? INVALID_INPUT);
    if ($prompt_id == INVALID_INPUT) {
        wp_die('Invalid prompt ID!');
    }
    $prompt_title = ($_POST['prompt_title'] ?? INVALID_INPUT);
    if ($prompt_title == INVALID_INPUT) {
        wp_die('Invalid prompt title!');
    }
    $prompt_description = ($_POST['prompt_description'] ?? INVALID_INPUT);
    if ($prompt_description == INVALID_INPUT) {
        wp_die('Invalid prompt description!');
    }
    $prompt_prompt = ($_POST['prompt_prompt'] ?? INVALID_INPUT);
    if ($prompt_prompt == INVALID_INPUT) {
        wp_die('Invalid prompt prompt!');
    }
    $prompt_tool = ($_POST['prompt_tool'] ?? INVALID_INPUT);
    if ($prompt_tool == INVALID_INPUT) {
        wp_die('Invalid prompt tool!');
    }
    $prompt_toolLink = ($_POST['prompt_toolLink'] ?? INVALID_INPUT);
    if ($prompt_toolLink == INVALID_INPUT) {
        wp_die('Invalid prompt tool link!');
    }
    $prompt_promptPattern = ($_POST['prompt_promptPattern'] ?? INVALID_INPUT);
    if ($prompt_promptPattern == INVALID_INPUT) {
        wp_die('Invalid prompt prompt pattern!');
    }
    $prompt_sector = ($_POST['prompt_sector'] ?? INVALID_INPUT);
    if ($prompt_sector == INVALID_INPUT) {
        wp_die('Invalid prompt sector!');
    }
  $prompt_imageLink = ($_POST['prompt_imageLink'] ?? INVALID_INPUT);
  if ($prompt_imageLink == INVALID_INPUT) {
    wp_die('Invalid prompt image link!');
  }

    global $wpdb;
    $winPL_prompt_table = $wpdb->prefix . 'winpl_prompt';
    $winPL_prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
    $winPL_sector_table = $wpdb->prefix . 'winpl_sector';

    //for the promptpattern and the sector we need to get the id's from the database
    $promptPatternId = $wpdb->get_var($wpdb->prepare("SELECT id FROM $winPL_prompt_pattern_table WHERE title = %s", $prompt_promptPattern));
    $sectorId = $wpdb->get_var($wpdb->prepare("SELECT id FROM $winPL_sector_table WHERE title = %s", $prompt_sector));

    //if the promptpattern or the sector is not found die with an error
    if ($promptPatternId == null) {
        wp_die('Invalid prompt pattern!');
    }
    if ($sectorId == null) {
        wp_die('Invalid prompt sector!');
    }

    //update the prompt
    $wpdb->update(
        $winPL_prompt_table,
        array(
            'title' => $prompt_title,
            'prompt' => $prompt_prompt,
            'description' => $prompt_description,
            'tool' => $prompt_tool,
            'toolLink' => $prompt_toolLink,
            'promptPattern' => $promptPatternId,
            'sector' => $sectorId,
            'imageLink' => $prompt_imageLink,
        ),
        array(
            'id' => $prompt_id
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d',
        ),
        array(
            '%d'
        )
    );
}

function winpl_add_prompt()
{
    winpl_check_user();

    $prompt_title = ($_POST['prompt_title'] ?? INVALID_INPUT);
    if ($prompt_title == INVALID_INPUT) {
        wp_die('Invalid prompt title!');
    }
    $prompt_description = ($_POST['prompt_description'] ?? INVALID_INPUT);
    if ($prompt_description == INVALID_INPUT) {
        wp_die('Invalid prompt description!');
    }
    $prompt_prompt = ($_POST['prompt_prompt'] ?? INVALID_INPUT);
    if ($prompt_prompt == INVALID_INPUT) {
        wp_die('Invalid prompt prompt!');
    }
    $prompt_tool = ($_POST['prompt_tool'] ?? INVALID_INPUT);
    if ($prompt_tool == INVALID_INPUT) {
        wp_die('Invalid prompt tool!');
    }
    $prompt_toolLink = ($_POST['prompt_toolLink'] ?? INVALID_INPUT);
    if ($prompt_toolLink == INVALID_INPUT) {
        wp_die('Invalid prompt tool link!');
    }
    $prompt_promptPattern = ($_POST['prompt_promptPattern'] ?? INVALID_INPUT);
    if ($prompt_promptPattern == INVALID_INPUT) {
        wp_die('Invalid prompt prompt pattern!');
    }
    $prompt_sector = ($_POST['prompt_sector'] ?? INVALID_INPUT);
    if ($prompt_sector == INVALID_INPUT) {
        wp_die('Invalid prompt sector!');
    }
  $prompt_imageLink = ($_POST['prompt_imageLink'] ?? INVALID_INPUT);
  if ($prompt_imageLink == INVALID_INPUT) {
    wp_die('Invalid prompt image link!');
  }

    global $wpdb;
    $winPL_prompt_table = $wpdb->prefix . 'winpl_prompt';
    $winPL_prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
    $winPL_sector_table = $wpdb->prefix . 'winpl_sector';

    //for the promptpattern and the sector we need to get the id's from the database
    $promptPatternId = $wpdb->get_var($wpdb->prepare("SELECT id FROM $winPL_prompt_pattern_table WHERE title = %s", $prompt_promptPattern));
    $sectorId = $wpdb->get_var($wpdb->prepare("SELECT id FROM $winPL_sector_table WHERE title = %s", $prompt_sector));

    //if the promptpattern or the sector is not found die with an error
    if ($promptPatternId == null) {
        wp_die('Invalid prompt pattern!');
    }
    if ($sectorId == null) {
        wp_die('Invalid prompt sector!');
    }

    //insert the prompt
    $wpdb->insert(
        $winPL_prompt_table,
        array(
            'title' => $prompt_title,
            'prompt' => $prompt_prompt,
            'description' => $prompt_description,
            'tool' => $prompt_tool,
            'toolLink' => $prompt_toolLink,
            'promptPattern' => $promptPatternId,
            'sector' => $sectorId,
            'imageLink' => $prompt_imageLink,
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d',
        )
    );
}

function winpl_delete_prompt()
{
    winpl_check_user();

    $prompt_id = ($_POST['prompt_id'] ?? INVALID_INPUT);
    if ($prompt_id == INVALID_INPUT) {
        wp_die('Invalid prompt ID!');
    }

    global $wpdb;
    $winPL_prompt_table = $wpdb->prefix . 'winpl_prompt';

    $wpdb->delete(
        $winPL_prompt_table,
        array(
            'id' => $prompt_id
        ),
        array(
            '%d'
        )
    );
}

