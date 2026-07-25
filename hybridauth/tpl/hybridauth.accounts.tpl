<!-- BEGIN: MAIN -->
<div class="d-grid gap-2">
    <!-- BEGIN: HYBRID_ACCOUNT -->
    <div class="d-flex align-items-center justify-content-between p-3 border rounded-3">
        <div class="d-flex align-items-center gap-3">
            <i class="fa-brands fa-{HYBRID_ACCOUNT_CODE} fs-4 text-secondary"></i>
            <div>
                <strong>{HYBRID_ACCOUNT_NAME}</strong>
                <!-- IF {HYBRID_ACCOUNT_LINKED} -->
                    <span class="badge bg-success ms-2">{PHP.L.hybridauth_connected}</span>
                    <!-- IF {HYBRID_ACCOUNT_PROFILE} -->
                    <a href="{HYBRID_ACCOUNT_PROFILE}" class="ms-2 small" target="_blank" rel="noopener noreferrer">{PHP.L.Profile}</a>
                    <!-- ENDIF -->
                <!-- ELSE -->
                    <span class="badge bg-secondary ms-2">{PHP.L.hybridauth_disconnected}</span>
                <!-- ENDIF -->
            </div>
        </div>
        <div>
            <a href="{HYBRID_ACCOUNT_LINK_URL}" class="btn btn-sm btn-outline-primary">
                {HYBRID_ACCOUNT_ACTION}
            </a>
        </div>
    </div>
    <!-- END: HYBRID_ACCOUNT -->
</div>
<!-- END: MAIN -->