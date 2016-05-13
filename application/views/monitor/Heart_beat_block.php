<div class="">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $companyname; ?> <span class="loading_img"><img src="<?php echo $this->include_path; ?>images/loading2.gif" style="display:none;opacity: 0.6;filter: alpha(opacit=60);" /></span><small><?php // echo $head_beat_status; ?></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li class="current-page"><a href="#" class="oracle_app_monitor_heart_beat_refresh_btn" cid="<?php echo $cid; ?>"><i class="fa fa-refresh"></i> Refresh</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if ($status == 'inactive'): ?>
                    <span style="color:red"><?php echo $error_msg; ?></span>
                <?php else: ?>
                    <div aria-multiselectable="true" id="accordion_<?php echo $companyname; ?>" class="accordion">
                        <?php foreach($heart_beat as $key => $val): ?>
                            <?php 
                            $barname = md5($companyname.$key); 
                            $title = $key;
                            ?>
                            <div class="panel inner_accordion">
                                <a aria-controls="collap_<?php echo $barname; ?>" aria-expanded="true" href="#collap_<?php echo $barname; ?>" data-parent="#accordion_<?php echo $companyname; ?>" data-toggle="collapse" id="head_<?php echo $barname; ?>" role="tab" class="panel-heading inner-head" style="border:1px solid #ddd">
                                    <h4 class="panel-title"><strong style="<?php echo !empty($val['status'] != 'active')?'color:red':''; ?>"><?php echo $title; ?></strong></h4>
                                </a>
                                <div aria-labelledby="head_<?php echo $barname; ?>" role="tabpanel" class="panel-collapse collapse" id="collap_<?php echo $barname; ?>">
                                    <div style="border:1px solid #ddd; border-top:0" class="panel-body fixcontent">
                                        <table class="table table-striped"><tbody>
                                        <?php foreach($val as $k2 => $v2 ): ?>
                                            <tr>
                                                <td style="text-align: right"><?php echo $k2; ?> : </td>
                                                <td><?php echo $v2; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody></table>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>


