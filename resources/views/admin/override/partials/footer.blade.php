<!-- Main Footer -->
<footer class="main-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <strong>Education Management System</strong> &copy; {{ date('Y') }}
                <span class="hidden-xs">
                    All rights reserved.
                </span>
            </div>
            <div class="col-md-6">
                <div class="pull-right hidden-xs">
                    @if(config('app.env') !== 'production')
                        <span class="label label-warning">{{ strtoupper(config('app.env')) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>