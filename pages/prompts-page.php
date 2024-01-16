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
                        max-width: 100%;
                        overflow-x: auto;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }

                    @media (max-width: 767px) {
                        table {
                            display: block;
                            overflow-x: auto;
                            white-space: nowrap;
                        }
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

                    .copy-button {
                        position: absolute;
                        right: 0;
                        cursor: pointer;
                        background-color: #112A46;
                        color: white;
                        padding: 6px 12px;
                        border-radius: 4px;
                        color: white;
                        border: none;
                        text-align: center;
                        display: inline-block;
                        font-size: 14px;
                        margin: 2px;
                    }
                </style>

                <table>
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Prompt</th>
                            <th>Beschrijving</th>
                            <th>Tool</th>
                            <th>Prompt Pattern</th>
                            <th>Sector</th>
                            <th></th>
                            <th>Tool Link </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prompts as $prompt) : ?>
                            <tr id="<?php echo $prompt->id; ?>">
                                <td width="16%"><?php echo $prompt->title; ?></td>
                                <td width="30%">
                                    <div>
                                        <?php echo str_replace('\\', '', $prompt->prompt); ?>
                                        <button class="copy-button" onclick="copyToClipboard('<?php echo str_replace('\\', '', $prompt->prompt); ?>')">Copy</button>
                                    </div>
                                </td>
                                <td width="20%"><?php echo str_replace('\\', '', $prompt->description); ?></td>
                                <td width="9%">
                                    <?php
                                    if (!empty($prompt->imageLink)) {
                                        echo "<img class='prompt-image' src=" . $prompt->imageLink . " alt='Image'>";
                                    }
                                    echo $prompt->tool;
                                    ?>
                                </td>
                                <td width="9%"><?php echo $prompt->promptPattern; ?></td>
                                <td width="9%"><?php echo $prompt->sector; ?></td>
                                <td width="9%"><a href="<?php echo $prompt->toolLink; ?>" target="_blank">Probeer het zelf ></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </main>
</div>

<script>
    // scroll to right prompt id
    const urlParams = new URLSearchParams(window.location.search);
    const promptId = urlParams.get('id');
    if (promptId) {
        const element = document.getElementById(promptId);
        element.scrollIntoView();
        element.style.backgroundColor = '#ffff00';
    }

    function copyToClipboard(text) {
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    }
</script>

<?php get_footer(); ?>