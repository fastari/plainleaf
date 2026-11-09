# Brand preset file

A brand preset is one JSON file that holds a site's look and words: colors, fonts,
custom CSS, logo and photo files, theme settings and site text. The `rebrand` skill
exports one, and applies one to a fresh Plainleaf install.

Keep your preset (and the image files it points to) somewhere private, such as a
private Git repository. It never goes into the theme.

## Format

```json
{
  "plainleaf_preset": 1,
  "name": "My site",
  "exported": "2026-11-08",

  "site": {
    "blogname": "My site",
    "blogdescription": "Notes, stories and ideas worth keeping."
  },

  "font_pairing": "editorial",

  "global_styles": {
    "settings": {
      "color": { "palette": { "theme": [ { "slug": "base", "name": "Background", "color": "#FFFFFF" } ] } }
    },
    "styles": {}
  },

  "css": "/* Additional CSS, for example art for the home hero layer */",

  "options": {
    "plainleaf_logo_mode": "auto",
    "plainleaf_show_credit": true,
    "plainleaf_disable_feeds": false,
    "plainleaf_hero_layout": "right",
    "plainleaf_newsletter_action": "https://example.com/subscribe",
    "plainleaf_newsletter_email_field": "email",
    "plainleaf_social_linkedin": "https://www.linkedin.com/in/example/",
    "plainleaf_text_hero_title": "Notes, stories and ideas worth keeping."
  },

  "media": {
    "plainleaf_logo": { "file": "files/logo.png", "alt": "My site" },
    "plainleaf_logo_phone": { "file": "files/icon-512.png" },
    "plainleaf_author_photo": { "file": "files/photo.webp", "alt": "Photo of the author" },
    "site_icon": { "file": "files/icon-512.png" },
    "plainleaf_hero_media": { "file": "files/hero.mp4" }
  },

  "content_fixes": [
    { "find": "class=\"old-class\"", "replace": "class=\"new-class\"", "post_types": ["post", "page", "wp_block"] }
  ]
}
```

| Key | What it does | How it's applied |
| --- | --- | --- |
| `site` | Site title and tagline | `blogname`, `blogdescription` options |
| `font_pairing` | One of the shipped pairings: `plain`, `editorial`, `warm`, `modern`, `clean` | Copies `plainleaf/styles/typography/NN-<slug>.json` → `settings.typography.fontFamilies.theme` in Global Styles |
| `global_styles` | Any theme.json `settings` and `styles` | Merged into Global Styles (Site Editor > Styles) |
| `css` | Additional CSS | Replaces Additional CSS (read and keep what's there first) |
| `options` | Theme settings and site text (`plainleaf_*` options) | One option each |
| `media` | Image files, paths relative to the preset file | Uploaded to the Media Library, the new ID stored in the named option (`site_icon` is the core Site Icon) |
| `content_fixes` | Find and replace in saved content, for class or block names that changed | Applied post by post, with a preview list first |

All keys are optional. Unknown keys are ignored.

### The 12 color slots

`base`, `surface`, `contrast`, `muted`, `line`, `brand`, `brand-deep`, `brand-ink`,
`on-brand`, `accent`, `accent-hover`, `on-accent`. They must pass the pairs in
`bin/check-contrast.py` (WCAG AA). `bin/make-palettes.py` shows how to derive them from
four or five anchor colors.

### Text keys

Every `plainleaf_text_<key>` matches a field on Appearance > Site text. The full list,
with defaults, is `plainleaf_text_sections()` in `plainleaf/inc/site-text.php`.
