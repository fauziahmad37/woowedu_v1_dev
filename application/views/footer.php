		</main>

		<footer class="site-footer pt-3">
            <div class="container">
                <div class="row d-none">
					<p class="fs-16 mb-0">Dipersembahkan oleh</p>
                    <p style="font-size: 30px; color: green; font-weight: bold;">WoowEdu</p>
                </div>
            </div>
        </footer>


        <!-- JAVASCRIPT FILES -->
		<script src="https://kit.fontawesome.com/b377b34fd7.js" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>        
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- <script src="<?=base_url('assets/js/jquery.min.js')?>"></script> -->
        <!-- <script src="<?=base_url('assets/js/bootstrap.bundle.min.js')?>"></script> -->
        <script src="<?=base_url('assets/js/jquery.sticky.js')?>"></script>
        <!-- <script src="<?=base_url('assets/js/click-scroll.js')?>"></script> -->
        <script src="<?=base_url('assets/js/custom.js')?>"></script>
        <?= !empty($page_js) ? add_js($page_js) : trim('') ?>
    </body>
</html>
