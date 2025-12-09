<div class="row breadcrumb">
    <div class="col-12">
		<div class="justify-content-start">
			<!-- <i class="bi bi-list fs-4 icon-menu"></i> -->
			<button id="btn-menu" class="btn btn-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
				<i class="bi bi-list fs-4 text-white"></i>
			</button>
		</div>
        <div class="page-title-box d-flex align-items-center justify-content-end">

            <div class="page-title-right">
                <ol class="breadcrumb m-0" id="breadcrumb-main">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard/') ?>">Dashboards</a></li>
                    <?php 
                        $str = base_url(); $i = 0;
                        foreach ($this->uri->segments as $segment): 
                    ?>
                        <?php 
							if($i == 2) break; // buat ngilangin uri ke 3 biar id detail nya gak keliatan di layar
                            $url = substr($this->uri->uri_string, 0, strpos($this->uri->uri_string, $segment)) . $segment;
                            $is_active =  $url == $this->uri->uri_string;
                            $str .= $segment.'/';
                        ?>
                        <li class="breadcrumb-item <?php echo $is_active ? 'active': '' ?>">
                            <?php if($is_active): ?>
                                <?php  echo ucwords(str_replace('-', ' ', $segment));?>
                            <?php else: ?>
                                <a role="button" href="<?=$str?>"><?php $sss = preg_split('/(?=[A-Z])/', $segment, -1, PREG_SPLIT_NO_EMPTY); echo ucwords(str_replace('-', ' ', $sss[0])) ?></a>
                            <?php 
                                $i++;
                                endif; 
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>

        </div>
    </div>
</div>
