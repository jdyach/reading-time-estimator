# Reading Time Estimator

Automatically adds a "X min read" label to WordPress posts. Words-per-minute, label format, display position, and active post types are all configurable via a settings page.

[![Try it live in WordPress Playground](https://img.shields.io/badge/Try%20it%20live-WordPress%20Playground-3858e9?logo=wordpress&logoColor=white&style=for-the-badge)](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/jdyach/reading-time-estimator/main/_playground/blueprint.json)

The live demo runs a real WordPress site in your browser with this plugin, the [Fieldnote](https://github.com/jdyach/fieldnote) block theme and sample content. It opens a long article showing its "min read" label. A short post is included for comparison. Nothing to install.

## Features
- Auto-injects the badge before or after post content via `the_content` filter
- `[reading_time]` shortcode for manual placement anywhere
- Configurable WPM (default 200), label format with `{time}` placeholder, and post types
- Settings page under **Settings → Reading Time**
- Minimum display of 1 minute

## WordPress Concepts Demonstrated
| Concept | Where Used |
|---|---|
| `the_content` filter | `class-rte-output.php` |
| `add_shortcode` | `class-rte-output.php` |
| `wp_strip_all_tags` + `str_word_count` | `class-rte-calculator.php` |
| Settings API | `class-rte-settings.php` |
| `register_activation_hook` | `reading-time-estimator.php` |
| `get_post_types` loop | `class-rte-settings.php` |

## File Structure
```
reading-time-estimator/
├── reading-time-estimator.php
├── README.md
├── assets/css/front.css
└── includes/
    ├── class-rte-calculator.php
    ├── class-rte-output.php
    └── class-rte-settings.php
```

## Requirements
- WordPress 6.0+
- PHP 8.0+

## License
GPL-2.0-or-later
