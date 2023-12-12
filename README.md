# PromptLibrary WordPress Plugin

## Description

PromptLibrary is a WordPress plugin that provides a REST API for managing prompts. It allows users to create, retrieve, and manage prompts.

## Installation

1. Download the plugin files.
2. Upload the plugin files to the `/wp-content/plugins/PromptLibrary` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.

## Usage

The plugin registers several endpoints under the `winpl/v1` namespace:

- `GET /prompts`: Retrieves all prompts.
- `GET /prompts/{id}`: Retrieves a specific prompt by its ID.
- `POST /prompts`: Creates a new prompt.
- `GET /prompt-patterns`: Retrieves all prompt patterns.
- `GET /sectors`: Retrieves all sectors.

All endpoints require the user to be logged in. The `POST /prompts` endpoint additionally requires the user to have the `edit_posts` capability.

## Versioning

The plugin version is added to the headers of every API response as `X-Windesheim-Prompts-version`.

## License

This project is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).