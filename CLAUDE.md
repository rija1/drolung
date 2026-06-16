# CLAUDE.md — WordPress docroot

This is the WordPress webroot (where `wp-config.php` lives). The authoritative project context is in the **root `CLAUDE.md` one level up**:

```
~/Local Sites/drolung/CLAUDE.md         ← READ THIS FIRST
~/Local Sites/drolung/app/public/       ← you are here
```

Always start Claude Code from the project root (`~/Local Sites/drolung/`) so the symlinked `mockups/` directory is reachable and the root CLAUDE.md gets loaded automatically.

## Notes specific to this directory

- `wp-config.php` defines multisite constants (`MULTISITE`, `SUBDOMAIN_INSTALL`, etc.) and DB credentials (Local WP defaults: db `local`, user/pass `root`/`root`).
- `wp-content/` holds the only files you should ever edit:
  - `themes/drolung-base/`, `themes/drolung-org/`, `themes/drolung-branch/`, `themes/drolung-duk/`
  - `mu-plugins/*.php` and `mu-plugins/drolung-network/`
- `mock1/` is **historical** — design exploration superseded by the active mockups at `mockups/mockup-{dsm,dsf,duk}/`. Don't add new work there.

For everything else — architecture, conventions, porting workflow, pitfalls — see the root CLAUDE.md.
