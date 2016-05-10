<div class="">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $companyname; ?> <small><?php echo $head_beat_status; ?></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li class="current-page"><a href="#" class="oracle_app_monitor_head_beat_refresh_btn" cid="<?php echo $cid; ?>"><i class="fa fa-refresh"></i> Refresh</a>
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
                <table class="table table-striped">

                    <tbody>
                        <tr>
                            <td style="text-align: right;width:40%">Clear Gateway : </td>
                            <td style="width:60%"><?php echo $clear_gateway_accumulate_amount; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:40%">Overdue Deposit : </td>
                            <td style="width:60%"><?php echo $handle_overdue_deposit; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:40%">Member Level Upgrade : </td>
                            <td style="width:60%"><?php echo $monthly_member_upgrade; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:40%">Member Level Pre Upgrade : </td>
                            <td style="width:60%"><?php echo $monthly_member_upgrade_pre; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:40%">Reject Friend Referral : </td>
                            <td style="width:60%"><?php echo $event_reject_friend_referral; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:40%">Agent Comm Pre : </td>
                            <td style="width:60%"><?php echo $monthly_agent_commission_send_pre; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:40%">Agent Comm Sent : </td>
                            <td style="width:60%"><?php echo $monthly_agent_commission_send; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:40%">Monthly Agent Suspend : </td>
                            <td style="width:60%"><?php echo $monthly_agent_suspend; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;width:40%">Game Balance : </td>
                            <td style="width:60%"><?php echo $game_balance; ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>