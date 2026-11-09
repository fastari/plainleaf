---
name: publish-from-notion
description: Take a finished blog post from Notion and put it on WordPress through the AI Engine MCP - formatted blocks, title, excerpt, SEO title and description, category and tags, featured image - then share a preview link and schedule it for its due date. Never deletes content. Use when the user wants to publish, schedule or move a post from Notion to WordPress.
---

# Publish a post from Notion to WordPress

Tools: the Notion MCP (`notion-search`, `notion-fetch`, `notion-query-data-sources`) and
the WordPress MCP (AI Engine). Never delete or trash anything, in Notion or WordPress.
Never publish immediately unless the user explicitly says so: the default is a scheduled
post the user has previewed.

## 1. Find the post

Ask which Notion page or database to use if you don't know it (don't assume IDs; a site
owner may keep theirs in their own CLAUDE.md or memory). Fetch the page. Confirm with the
user: title, status (it should be marked finished/ready), due date, topic/category, tags.
Stop and ask if the status isn't finished or there is no due date.

## 2. Prepare the content

- Convert to WordPress blocks: headings (h2/h3, never h1), paragraphs, lists, quotes,
  images, embeds. Keep the author's words. Don't rewrite unless asked; fix only obvious
  typos and say which.
- Images: upload each with `wp_upload_media` (alt text from the caption or a short
  description), then use the new URLs. Pick the featured image (ask if unclear) and set
  it with `wp_set_featured_image`.
- Theme patterns you can use: the reflection box (`plainleaf/reflection`) for a closing
  question, if the post has one.
- Excerpt: 1-2 sentences, under about 160 characters, in the author's voice.
- SEO (`wp_get_seo` to see the plugin, then `wp_update_seo`): title under about 60
  characters, description about 150 characters, focus keyword if the plugin has one.
- Category: one existing topic (`wp_get_terms` taxonomy `category`); don't create new
  categories without asking. Tags: existing ones where they fit; ask before creating more.
  The `start-here` tag only if the user says so.

## 3. Create the draft

`wp_create_post` as a **draft** with title, content, excerpt, category, tags. If a draft
with the same title already exists, update that one instead of making a duplicate (ask
first). Then set SEO fields and the featured image.

## 4. Preview

Get a preview link with `wp_preview_link` (share: true gives a link that works without
logging in, valid a few days). Send it to the user with a short summary: title, excerpt,
SEO title/description, category, tags, scheduled time. Wait for approval or changes.

## 5. Schedule

After approval, `wp_update_post` with `post_status: future` and `post_date` set to the
due date (ask for the time if none is given; use the site's timezone from
`wp_get_site_info`). Confirm the scheduled date back to the user. If the date has already
passed, ask whether to publish now or pick a new date.

## 6. Close the loop

Offer to update the Notion page (status "Scheduled", WordPress link). Only change the
fields the user agrees to.
