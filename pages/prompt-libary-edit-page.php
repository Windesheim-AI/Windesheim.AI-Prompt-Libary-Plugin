<?php
function windesheim_prompt_libary_add_setting_page()
{
    add_menu_page(
        // Page title
        'Windesheim Prompt Libary',
        // Menu title
        'Prompt Libary',
        // Capability
        'manage_options',
        // Menu slug
        'windesheim-prompt-libary',
        // Function to render the settings page
        'windesheim_prompt_libary_render_settings_page',
        // Icon URL use ./images/your-icon.png
        'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxzdmcgaWQ9ImxvZ29zYW5kdHlwZXNfY29tIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNTAgMTUwIj4NCiAgICA8cGF0aCBmaWxsPSIjRkZjYjA1Ig0KICAgICAgICBkPSJNNDAuNDcsMTMzLjU4bDE5LjcyLTExLjg1LDUuNjItMzkuOTQsMTUuODQsNTEuOCwyNy4zMS0xNi40MUwxNDEuMDcsNS4xNWMtMTEuNzQsMi42NC0yMy4yNSw2LjIzLTM0LjQxLDEwLjcybC0xNC45Nyw3MC40LTEwLjI5LTU4LjM2Yy0xMC45Myw2LjA4LTIxLjM0LDEzLjA2LTMxLjExLDIwLjg3bC02LjcxLDQ3Ljc0LTExLjkyLTMxLjExYy03Ljc0LDcuNzMtMTQuOTIsMTYuMDEtMjEuNDcsMjQuNzdsLS4wNCwuMDksMzAuMyw0My4zWiIgLz4NCjwvc3ZnPg==',
        50 // Position
    );
}

add_action('admin_menu', 'windesheim_prompt_libary_add_setting_page');
function windesheim_prompt_libary_render_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Windesheim Prompt Libary</h1>
        <p>Manage your prompts here.</p>
        <a href="#TB_inline?width=600&height=550&inlineId=edit-propmpt-modal"
            class="thickbox button button-primary btn_add_prompt">Add
            Prompt</a>
        <?php
        // get all the prompts
        global $wpdb;
        $prompt_table = $wpdb->prefix . 'winpl_prompt';
        $prompt_pattern_table = $wpdb->prefix . 'winpl_prompt_pattern';
        $sector_table = $wpdb->prefix . 'winpl_sector';
        $prompts = $wpdb->get_results("SELECT * FROM $prompt_table");
        foreach ($prompts as $prompt) {
            $prompt->promptPattern = $wpdb->get_var($wpdb->prepare("SELECT title FROM $prompt_pattern_table WHERE id = %d", $prompt->promptPattern));
            $prompt->sector = $wpdb->get_var($wpdb->prepare("SELECT title FROM $sector_table WHERE id = %d", $prompt->sector));
        }

        //get the posible prompt patterns and sectors with their id's
        $prompt_patterns = $wpdb->get_results("SELECT * FROM $prompt_pattern_table");
        $sectors = $wpdb->get_results("SELECT * FROM $sector_table");

        // display the prompts
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                        <a href="#">
                            <span>Title</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="prompt" class="manage-column column-prompt">Prompt</th>
                    <th scope="col" id="description" class="manage-column column-description">Description</th>
                    <th scope="col" id="tool" class="manage-column column-tool">Tool</th>
                    <th scope="col" id="toolLink" class="manage-column column-toolLink">Tool Link</th>
                    <th scope="col" id="promptPattern" class="manage-column column-promptPattern">Prompt Pattern</th>
                    <th scope="col" id="sector" class="manage-column column-sector">Sector</th>
                    <th scope="col" id="imageLink" class="manage-column column-imageLink">Image Link</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                foreach ($prompts as $prompt) {
                    ?>
                    <tr id="prompt-<?php echo $prompt->id; ?>"
                        class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-uncategorized">
                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                            <strong>
                                <a class="row-title" href="#" aria-label="“<?php echo $prompt->title; ?>” (Edit)">
                                    <?php echo $prompt->title; ?>
                                </a>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                    <a class="thickbox-edit" href="#TB_inline?width=600&height=550&inlineId=edit-propmpt-modal"
                                        aria-label="Edit “<?php echo $prompt->title; ?>”">Edit</a>
                                    |
                                </span>
                                <span class="trash">
                                    <a href="#" class="btn_delete_prompt"
                                        aria-label="Move “<?php echo $prompt->title; ?>” to the Trash">Trash</a>
                                </span>
                            </div>
                            <button type="button" class="toggle-row">
                                <span class="screen-reader-text">Show more details</span>
                            </button>
                        </td>
                        <td class="prompt column-prompt" data-colname="Prompt">
                            <?php echo $prompt->prompt; ?>
                        </td>
                        <td class="description column-description" data-colname="Description">
                            <?php echo $prompt->description; ?>
                        </td>
                        <td class="tool column-tool" data-colname="Tool">
                            <?php echo $prompt->tool; ?>
                        </td>
                        <td class="toolLink column-toolLink" data-colname="Tool Link">
                            <?php echo $prompt->toolLink; ?>
                        </td>
                        <td class="promptPattern column-promptPattern" data-colname="Prompt Pattern">
                            <?php echo $prompt->promptPattern; ?>
                        </td>
                        <td class="sector column-sector" data-colname="Sector">
                            <?php echo $prompt->sector; ?>
                        </td>
                        <td class="imageLink column-imageLink" data-colname="ImageLink">
                          <?php echo $prompt->imageLink; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Modals -->
        <div id="edit-propmpt-modal" style="display:none;">
            <form action="post">
                <input type="hidden" class="form-control" id="edit_prompt_id" name="edit_prompt_id" style="width: 80%;">
                <input type="hidden" class="form-control" id="action_prompt_modal" name="action_prompt_modal"
                    style="width: 80%;">
                <div class="form-group">
                    <label for="edit_prompt_title">Title</label>
                    <br />
                    <input type="text" class="form-control" id="edit_prompt_title" name="edit_prompt_title"
                        style="width: 80%;">
                </div>

                <div class="form-group">
                    <label for="edit_prompt_description">Description</label>
                    <br />
                    <textarea class="form-control" id="edit_prompt_description" name="edit_prompt_description"
                        style="width: 80%;" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_prompt_prompt">Prompt</label>
                    <br />
                    <textarea class="form-control" id="edit_prompt_prompt" name="edit_prompt_prompt" style="width: 80%;"
                        rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_prompt_tool">Tool</label>
                    <br />
                    <input type="text" class="form-control" id="edit_prompt_tool" name="edit_prompt_tool"
                        style="width: 80%;">
                </div>

                <div class="form-group">
                    <label for="edit_prompt_toolLink">Tool Link</label>
                    <br />
                    <input type="url" class="form-control" id="edit_prompt_toolLink" name="edit_prompt_toolLink"
                        style="width: 80%;">
                </div>

                <div class="form-group">
                    <label for="edit_prompt_imageLink">Image Link</label>
                    <br />
                    <input type="url" class="form-control" id="edit_prompt_imageLink" name="edit_prompt_imageLink"
                           style="width: 80%;">
                </div>

                <div class="form-group">
                    <label for="edit_prompt_promptPattern">Prompt Pattern</label>
                    <br />
                    <!-- make an option list with the avalible prompt patterns -->
                    <select class="form-control" id="edit_prompt_promptPattern" name="edit_prompt_promptPattern"
                        style="width: 80%;">
                        <?php
                        foreach ($prompt_patterns as $prompt_pattern) {
                            ?>
                            <option value="<?php echo $prompt_pattern->title; ?>">
                                <?php echo $prompt_pattern->title; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_prompt_sector">Sector</label>
                    <br />
                    <!-- make an option list with the avalible sectors -->
                    <select class="form-control" id="edit_prompt_sector" name="edit_prompt_sector" style="width: 80%;">
                        <?php
                        foreach ($sectors as $sector) {
                            ?>
                            <option value="<?php echo $sector->title; ?>">
                                <?php echo $sector->title; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <a type="submit" class="button button-primary btn_save_prompt_edit">Save</a>
                <a type="" class="button button-primary-outline btn_cancel_edit">Cancel</a>


            </form>
        </div>

    </div>
    <script type="text/javascript" nonce=<?php echo uniqid(); ?>>
        jQuery(document).ready(function ($) {
            $('.thickbox-edit').click(function (e) {
                e.preventDefault();
                var href = $(this).attr('href');

                // get witch prompt is selected
                var prompt_id = $(this).closest('tr').attr('id').replace('prompt-', '');
                console.log(prompt_id);

                // set the values of the form using the prompt id
                var prompts = <?php echo json_encode($prompts); ?>;
                var prompt = prompts.find(p => p.id == prompt_id);

                $('#action_prompt_modal').val('edit');
                $('#edit_prompt_id').val(prompt.id);
                $('#edit_prompt_title').val(prompt.title);
                $('#edit_prompt_description').val(prompt.description);
                $('#edit_prompt_prompt').val(prompt.prompt);
                $('#edit_prompt_tool').val(prompt.tool);
                $('#edit_prompt_toolLink').val(prompt.toolLink);
                $('#edit_prompt_imageLink').val(prompt.imageLink);
                $('#edit_prompt_promptPattern').val(prompt.promptPattern);
                $('#edit_prompt_sector').val(prompt.sector);

                tb_show('Add Course', href);
            });
            $('.btn_cancel_edit').click(function (e) {
                e.preventDefault();
                tb_remove();
            });
            $('.btn_save_prompt_edit').click(function (e) {
                e.preventDefault();

                // get the values of the form
                var action = $('#action_prompt_modal').val();
                var data = {
                    'action': action == 'edit' ? 'winpl_edit_prompt' : 'winpl_add_prompt',
                    'prompt_id': $('#edit_prompt_id').val(),
                    'prompt_title': $('#edit_prompt_title').val(),
                    'prompt_description': $('#edit_prompt_description').val(),
                    'prompt_prompt': $('#edit_prompt_prompt').val(),
                    'prompt_tool': $('#edit_prompt_tool').val(),
                    'prompt_toolLink': $('#edit_prompt_toolLink').val(),
                    'prompt_imageLink': $('#edit_prompt_imageLink').val(),
                    'prompt_promptPattern': $('#edit_prompt_promptPattern').val(),
                    'prompt_sector': $('#edit_prompt_sector').val()
                };

                // update the prompt
                $(this).html('<span class="spinner is-active"></span>');
                $(this).prop('disabled', true).html('<span class="spinner is-active"></span>');

                $.post(ajaxurl, data, function (response) {
                    location.reload();
                });
            });
            $('.btn_delete_prompt').click(function (e) {
                e.preventDefault();
                if (confirm("Are you sure you want to delete this prompt?")) {
                    var prompt_id = $(this).closest('tr').attr('id').replace('prompt-', '');
                    var data = {
                        'action': 'winpl_delete_prompt',
                        'prompt_id': prompt_id,
                    };

                    $(this).html('<span class="spinner is-active"></span>');
                    $(this).prop('disabled', true).html('<span class="spinner is-active"></span>');

                    $.post(ajaxurl, data, function (response) {
                        location.reload();
                    });
                }
            });
            $('.btn_add_prompt').click(function (e) {
                e.preventDefault();
                $('#action_prompt_modal').val('add');
                $('#edit_prompt_id').val('');
                $('#edit_prompt_title').val('');
                $('#edit_prompt_description').val('');
                $('#edit_prompt_prompt').val('');
                $('#edit_prompt_tool').val('');
                $('#edit_prompt_toolLink').val('');
                $('#edit_prompt_imageLink').val('');
                $('#edit_prompt_promptPattern').val('');
                $('#edit_prompt_sector').val('');

            });
        });
    </script>
    <?php

}

// add the ajax actions
add_action('wp_ajax_winpl_add_prompt', 'winpl_add_prompt');
add_action('wp_ajax_winpl_edit_prompt', 'winpl_edit_prompt');
add_action('wp_ajax_winpl_delete_prompt', 'winpl_delete_prompt');
