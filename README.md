Foowie\PermissionChecker
===========================

Installation
------------
Include extension in config.neon
```
extensions:
	permission: Foowie\PermissionChecker\DI\PermissionExtension
```
and use PresenterPermissionTrait in your base presenter.

Usage
-----
You can annotate presenter class, action and render methods and signal methods with these annotations:
```
@loggedIn - logged user is required
@loggedIn(false) - unlogged user is required
@role(superadmin, admin) - user must have role admin or superadmin
@resource(administration) - user must be assigned to administration resource
```

In template files is allowed to user these macros:
```
{ifAllowed 'administration'}{/ifAllowed} - user must be assigned to administration resource
{ifAllowedLink ':Administration:Dashboard:'}{/ifAllowed} - user must be allowed to display that page
<a n:ifAllowedHref=':Administration:Dashboard:'></a> - user must be allowed to display that page, shorter alternative is "allowedHref"
```

