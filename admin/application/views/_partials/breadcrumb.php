<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"><span class="text-primary"><?php echo SITE_NAME ?></span></h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0" id="breadcrumb-main">
                    <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard/') ?>">Dashboards</a></li>
                    <?php 
                        $str = base_url(); $i = 0;
                        foreach ($this->uri->segments as $segment): 
                    ?>
                        <?php 
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