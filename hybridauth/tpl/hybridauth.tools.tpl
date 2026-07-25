<!-- BEGIN: MAIN -->
<h2>{HYBRIDAUTH_LANG_TITLE}</h2>
{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<p><a href="{HYBRIDAUTH_UPDATE_URL}" class="btn btn-primary">{HYBRIDAUTH_LANG_UPDATE}</a></p>

<h3>{HYBRIDAUTH_LANG_STATUS}</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>{HYBRIDAUTH_LANG_NAME}</th>
            <th>{HYBRIDAUTH_LANG_CODE}</th>
            <th>{HYBRIDAUTH_LANG_ENABLED}</th>
            <th>{HYBRIDAUTH_LANG_FIELDS_LIST}</th>
            <th>{HYBRIDAUTH_LANG_FIELDS_STATUS}</th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: PROVIDER_ROW -->
        <tr>
            <td>{PROVIDER_NAME}</td>
            <td>{PROVIDER_CODE}</td>
            <td>{PROVIDER_ENABLED}</td>
            <td>{PROVIDER_FIELDS_LIST}</td>
            <td>{PROVIDER_FIELDS}</td>
        </tr>
        <!-- END: PROVIDER_ROW -->
    </tbody>
</table>
<!-- END: MAIN -->