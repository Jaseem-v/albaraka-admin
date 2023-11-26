<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                {{ @DB::table('settings')->first()->copyright }}  | Developed by <a href="https://techinwallet.com" target="blank">TechinWallet</a>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    
                </div>
            </div>
        </div>
    </div>
</footer>