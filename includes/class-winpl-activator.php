<?php

class WinPL_Activator
{
	public static function activate()
	{
		global $wpdb;
		flush_rewrite_rules();
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$winPL_prompt_table = $wpdb->prefix . 'winpl_prompt';
		$winPL_prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
		$winPL_sector_table = $wpdb->prefix . 'winpl_sector';

		// Create winpl_sector table
		$charset_collate = $wpdb->get_charset_collate();
		$winpl_sector_sql = "CREATE TABLE IF NOT EXISTS $winPL_sector_table (
			id INT NOT NULL AUTO_INCREMENT,
			title VARCHAR(255),
			PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta($winpl_sector_sql);

		// Create winpl_prompt_pattern table
		$winpl_prompt_pattern_sql = "CREATE TABLE IF NOT EXISTS $winPL_prompt_pattern_table (
			id INT NOT NULL AUTO_INCREMENT,
			title VARCHAR(255),
			PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta($winpl_prompt_pattern_sql);

		// Create winpl_prompt table
		$winpl_prompt_sql = "CREATE TABLE IF NOT EXISTS $winPL_prompt_table (
			id INT NOT NULL AUTO_INCREMENT,
			title VARCHAR(255),
			prompt TEXT,
			description TEXT,
			tool VARCHAR(255),
			toolLink VARCHAR(255),
			promptPattern INT,
			sector INT,
			imageLink TEXT,
			PRIMARY KEY  (id),
			FOREIGN KEY (promptPattern) REFERENCES $winPL_prompt_pattern_table(id),
			FOREIGN KEY (sector) REFERENCES $winPL_sector_table(id)
		) $charset_collate;";
		dbDelta($winpl_prompt_sql);

		// Seed the tables if they are empty
		$winPL_prompt_pattern_count = $wpdb->get_var("SELECT COUNT(*) FROM $winPL_prompt_pattern_table");
		if ($winPL_prompt_pattern_count == 0) {
			self::seed_prompt_patterns();
		}
		$winPL_sector_count = $wpdb->get_var("SELECT COUNT(*) FROM $winPL_sector_table");
		if ($winPL_sector_count == 0) {
			self::seed_sectors();
		}
	}

	private static function seed_sectors()
	{
		global $wpdb;
		$winPL_sector_table = $wpdb->prefix . 'winpl_sector';
		$wpdb->insert(
			$winPL_sector_table,
			array(
				'title' => 'Education'
			)
		);
		$wpdb->insert(
			$winPL_sector_table,
			array(
				'title' => 'Health'
			)
		);
		$wpdb->insert(
			$winPL_sector_table,
			array(
				'title' => 'Finance'
			)
		);
		$wpdb->insert(
			$winPL_sector_table,
			array(
				'title' => 'Retail'
			)
		);
		$wpdb->insert(
			$winPL_sector_table,
			array(
				'title' => 'ICT'
			)
		);
		$wpdb->insert(
			$winPL_sector_table,
			array(
				'title' => 'Manufacturing'
			)
		);
		$wpdb->insert(
			$winPL_sector_table,
			array(
				'title' => 'Other'
			)
		);
	}

	private static function seed_prompt_patterns()
	{
		global $wpdb;
		$winPL_prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Alternative Approaches Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Cognitive Verifier Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Context Manager Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Fact Check List Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Game Play Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Infinite Generator Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Meta Language Creation Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Persona Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Question Refinement Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Recipe Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Reflection Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Template Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'The Flipped Interaction Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'The Output Automater Pattern'
			)
		);
		$wpdb->insert(
			$winPL_prompt_pattern_table,
			array(
				'title' => 'Visualization Generator Pattern'
			)
		);
	}
}
