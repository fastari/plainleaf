# Plainleaf

A calm, text-first WordPress block theme for blogs and newsletters, built to be rebranded
and run with AI.

Your colors, fonts, logo and every sentence visitors read live in **WordPress settings**,
not in the theme files. So you can rebrand without touching code, and theme updates never
overwrite your look.

> I maintain this for my own sites, so it doesn't take outside pull requests or issues.
> Fork it if you want something different (see [Name](#name)). Security reports are
> welcome, privately: see [SECURITY.md](SECURITY.md).

## What you get

- **Neutral by default, yours in a minute:** 5 color palettes plus a dark one, and 5 font
  pairings, all in Appearance > Editor > Styles. Every palette passes WCAG AA contrast.
- **Everything editable in wp-admin:** logo, author photo, newsletter, social links and the
  footer credit in Appearance > Theme settings; all visible wording in Appearance > Site text.
- **Writer features:** newsletter signup that works with any tool, listen and share buttons
  on posts, topic pages and filters, related posts, a Start here page, and `/llms.txt`
  for AI tools.
- **Private and fast:** fonts are bundled. No CDNs, no trackers, no external requests.
- **Auto-updates from GitHub:** no account, no token.
- **AI skills:** Claude Code skills to rebrand, add features, publish from Notion, release,
  and review security.

## Quick start (no code)

1. Download `plainleaf-x.y.z.zip` from the [latest release](https://github.com/fastari/plainleaf/releases/latest).
2. In WordPress: **Appearance > Themes > Add New Theme > Upload Theme**, choose the zip,
   **Install Now**, then **Activate**.
3. **Appearance > Themes > Plainleaf > Enable auto-updates** to get new versions automatically.
4. Make it yours:
   - **Appearance > Editor > Styles:** pick a color palette and a font pairing.
   - **Appearance > Theme settings:** upload your logo and author photo, connect your
     newsletter, add social links.
   - **Settings > General:** site title, tagline and Site Icon.
   - **Appearance > Site text:** change any wording.
5. Recommended setup:
   - **Settings > Permalinks:** Post name.
   - Pages with these slugs: `home`, `blog`, `about`, and optionally `start-here` and
     `newsletter`. Menu links to About, Start here and Newsletter only appear once the page exists.
   - **Settings > Reading:** homepage = Home, posts page = Blog.
   - **Posts > Categories:** your topics. Tag posts `start-here` to list them on Start here.

## Quick start (Claude Code)

1. Clone this repo and open it in [Claude Code](https://claude.com/claude-code).
2. Connect your site's MCP server (for example the free
   [AI Engine](https://wordpress.org/plugins/ai-engine/) plugin's MCP) so Claude can read
   and change your WordPress settings.
3. Ask, for example:
   - "Install Plainleaf on my site and rebrand it in a warm, earthy style."
   - "Make it look and feel like https://example.com, but keep my logo."
   - "Publish my finished Notion post 'X' and schedule it for its due date."
   - "Export my branding to a preset file."

## Rebranding

| What | Where | Stored in |
| --- | --- | --- |
| Colors | Appearance > Editor > Styles > Colors | Global Styles (database) |
| Fonts | Styles > Typography (pairings, or your own via Manage fonts) | Global Styles / Font Library |
| Logo, phone logo, author photo | Appearance > Theme settings | Media Library + options |
| Browser/phone icon | Settings > General > Site Icon | Media Library |
| Wording | Appearance > Site text | Options |
| Home hero image or video | Appearance > Theme settings > Home hero (right half or whole band) | Media Library + options |
| Home hero art in code | Styles > Additional CSS, targeting `.pl-hero-art__layer > span` | Global Styles |

Everything above survives theme updates. To move a brand between sites, or keep a backup,
use a **brand preset file** ([format](docs/brand-preset.md)): the `rebrand` skill exports
one and applies one. Keep presets private, not in this repo.

The theme credit ("Theme by Fastari") is on by default. Thank you for keeping it. Turn it
off any time in Appearance > Theme settings > Theme credit.

## AI skills

In `.claude/skills/`. Claude Code picks them up automatically when you open this repo.

| Skill | What it does |
| --- | --- |
| `rebrand` | Colors, fonts, logo, hero and tone through WordPress settings, optionally inspired by a reference site; saves your look to a brand preset file and restores it on any install |
| `add-feature` | Adds a section, pattern, template or block following the theme's conventions |
| `publish-from-notion` | Formats a finished Notion post, sets SEO, category and tags, sends a preview link, schedules it. Never deletes |
| `release` | Bumps the version, writes the changelog, builds and publishes a GitHub release |
| `theme-security-review` | Audits the theme for WordPress security issues with PHPCS, Semgrep and a manual checklist |

The WordPress skills need an MCP connection to your site (for example AI Engine). They ask
before changing anything, and most changes can be undone from the MCP's change log.

## Updates

Releases are automatic. Every pull request that changes the theme (`plainleaf/`) carries
one label, `release:patch`, `release:minor`, `release:major` or `release:skip` (a required
check blocks the merge without one). Merging it publishes the next version, worked out from
the release tags, with the zip built and checked and release notes generated from the
merged pull requests. Changes to docs, CI or tooling don't release and need no label.

Sites check this repository's public releases every few hours (no token, no account) and
offer new versions in Dashboard > Updates, or install them on their own with auto-updates
on. Only zips from this repo's release downloads are accepted. Point a site at your own
fork with `define( 'PLAINLEAF_UPDATE_REPO', 'you/plainleaf' );` in `wp-config.php`, or turn
updates off with `define( 'PLAINLEAF_DISABLE_UPDATES', true );`. Git Updater also works (the
theme has `GitHub Theme URI` and `Release Asset` headers).

## Development

Needs Node 20+, PHP 8.x, Composer and Python 3.

```bash
npm install && composer install
make dev        # local WordPress at http://127.0.0.1:9400 with demo content
                # (logs you in automatically; if asked, the WordPress Playground
                #  default login is admin / password. Local only, never on a real site.)
make build      # checks (versions, PHP, JSON, contrast) and builds dist/plainleaf-x.y.z.zip
make lint       # PHPCS with WordPress Coding Standards
make palettes   # regenerate styles/colors from bin/make-palettes.py
make release    # release without a merge (BUMP=patch|minor|major); merges release on their own
```

Rules for changes are in [CONTRIBUTING.md](CONTRIBUTING.md). In short: design tokens only,
no hardcoded visible text, everything escaped, no build step, no external requests.

## Security

Report vulnerabilities privately; see [SECURITY.md](SECURITY.md). Every PR and a weekly
job run PHPCS (WordPress security sniffs) and Semgrep (code and secrets).

## Name

Forks and copies are welcome under the GPL. Please give yours a different name, and don't
present it as Plainleaf or as a Fastari release. The GPL covers the code, not the
Plainleaf or Fastari names.

## License

GPL-2.0-or-later, see [LICENSE](LICENSE). Forked from Twenty Twenty-Five by the WordPress
team. Bundled fonts are under the SIL Open Font License; credits in
[`plainleaf/readme.txt`](plainleaf/readme.txt).
