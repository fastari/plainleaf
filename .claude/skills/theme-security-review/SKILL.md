---
name: theme-security-review
description: Audit the Plainleaf theme code for WordPress security issues - output escaping, input sanitizing, nonces, capability checks, REST and meta permissions, redirects, file access, external requests and secrets - run PHPCS and Semgrep, and propose fixes. Use when the user asks for a security review, audit or hardening of the theme.
---

# Security review of the theme

Report findings first; change code only after the user agrees (or if they asked for fixes).

## 1. Run the scanners

```bash
composer install            # once
vendor/bin/phpcs            # WordPress standards incl. WordPress.Security (phpcs.xml.dist)
semgrep scan --config p/php --config p/security-audit --config p/secrets --metrics=off \
  --exclude node_modules --exclude vendor --exclude dist
```

If Semgrep isn't installed, use `pipx run semgrep ...` or Docker
(`docker run --rm -v "$PWD:/src" semgrep/semgrep semgrep scan ...`). The same checks run
in `.github/workflows/security.yml` on every PR and weekly.

## 2. Read the code the scanners can't judge

Go through `plainleaf/inc/*.php`, `plainleaf/blocks/*/render.php`, `plainleaf/patterns/*.php`:

- **Output:** every dynamic value escaped at the point of output (`esc_html`, `esc_attr`,
  `esc_url`, `wp_kses` with a tight allowlist). Check every `phpcs:ignore` comment: is the
  reason true?
- **Input:** `$_GET`, `$_POST`, `$_SERVER`, `$_COOKIE` unslashed and sanitized; settings
  registered with a `sanitize_callback`; meta registered with `sanitize_callback` and an
  `auth_callback` that checks `current_user_can`.
- **Admin pages and actions:** `current_user_can()` before rendering or saving; nonces on
  any form not using the Settings API (`check_admin_referer` / `wp_verify_nonce`).
- **REST:** settings and meta exposed with `show_in_rest` only expose what's safe to read
  for the users who can read them.
- **Redirects:** `wp_safe_redirect` only.
- **Remote requests:** fixed hosts only, timeouts set, responses validated before use.
  The updater must accept zips only from
  `https://github.com/<repo>/releases/download/` and never send a token.
- **Files:** no user-controlled paths in `include`/`require`/`file_get_contents`.
- **Front-end JS:** no `innerHTML` with untrusted data; `textContent` instead.
- **Secrets:** no keys, tokens, passwords or private URLs anywhere in the repo (also check
  git history before making a repo public).
- **Third parties:** no external scripts, fonts or trackers on the front end.

## 3. Report

A table: severity (high/medium/low), file:line, what's wrong, a concrete exploit scenario,
the fix. Say plainly when an area was checked and is fine. Fixes go on a branch with a PR;
add a CHANGELOG line under "Security".

Vulnerabilities from outside reporters arrive through GitHub private vulnerability
reporting (see `SECURITY.md`); don't discuss them in public issues.
