<?php
/*
Template Name: Prompts Page
*/

get_header();

require_once plugin_dir_path(__FILE__) . '../includes/class-winpl-endpoints.php';

function get_prompts()
{
    $endpoints = new WinPL_Endpoints();
    return $endpoints->get_prompts();
}

$prompts = get_prompts();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <article>
            <header class="entry-header">
                <h1 class="entry-title">Prompt Library</h1>
            </header>

            <div class="entry-content">
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }

                    #primary {
                        margin-left: 30px;
                        margin-right: 30px;
                    }

                    th,
                    td {
                        padding: 8px;
                        text-align: left;
                        border-bottom: 1px solid #ddd;
                        word-wrap: break-word;
                        max-width: 170px;
                    }

                    th {
                        background-color: #f2f2f2;
                    }

                    tr:hover {
                        background-color: #f5f5f5;
                    }

                    img.prompt-image {
                        width: 50px;
                    }
                </style>

                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Tool Link </th>
                            <th>Titel</th>
                            <th>Prompt</th>
                            <th>Beschrijving</th>
                            <th>Tool</th>
                            <th>Prompt Pattern</th>
                            <th>Sector</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prompts as $prompt) : ?>
                            <tr>
                                <td><img class="prompt-image" src="<?php echo $prompt->imageLink; ?>" alt="Image"></td>
                                <td><a href="<?php echo $prompt->toolLink; ?>" target="_blank">Probeer hem nu ></a></td>
                                <th><?php echo $prompt->title; ?></th>
                                <td><?php echo $prompt->prompt; ?></td>
                                <td><?php echo $prompt->description; ?></td>
                                <td><?php echo $prompt->tool; ?></td>
                                <td><?php echo $prompt->promptPattern; ?></td>
                                <td><?php echo $prompt->sector; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </main>
</div>

<?php get_footer(); ?>