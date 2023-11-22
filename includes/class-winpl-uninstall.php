<?php

class WinPL_Uninstall
{

    public static function uninstall()
    {
        global $wpdb;
        $winPL_prompt_table = $wpdb->prefix . 'winpl_prompt';
        $winPL_prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
        $winPL_sector_table = $wpdb->prefix . 'winpl_sector';

        $wpdb->query("DROP TABLE IF EXISTS $winPL_prompt_pattern_table");
        $wpdb->query("DROP TABLE IF EXISTS $winPL_sector_table");
        $wpdb->query("DROP TABLE IF EXISTS $winPL_prompt_table");
    }
}
