
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
