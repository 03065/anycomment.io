<?php

$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'anycomment-dashboard';
?>

<div class="wrap">
    <h2><?= __('Dashboard', 'anycomment') ?></h2>

    <div class="anycomment-dashboard">
        <div class="anycomment-dashboard__container">
            <header class="anycomment-dashboard__header">
                <div class="anycomment-dashboard__header-logo">
                    <img src="<?= AnyComment()->plugin_url() . '/assets/img/mini-logo.svg' ?>"
                         alt="<?= __('AnyComment', 'anycomment') ?>">
                    <h2><?= __('AnyComment', 'anycomment') ?></h2>
                </div>

                <div class="anycomment-dashboard__header-official">
                    <a href="https://anycomment.io" target="_blank"><?= __('Official Website', 'anycomment') ?></a>
                </div>

                <div class="clearfix"></div>
            </header>

            <div class="anycomment-dashboard__tabs" style="display: none">
                <ul>
                    <li><a href="#"><?= __('Dashboard', 'anycomment') ?></a></li>
                    <li><a href="#"><?= __('Social', 'anycomment') ?></a></li>
                    <li><a href="#"><?= __('Settings', 'anycomment') ?></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>


            <div class="anycomment-dashboard__splitter">
                <div class="anycomment-dashboard__splitter-half commentators">
                    <div class="anycomment-dashboard__splitter-half-center">
                        <img src="<?= AnyComment()->plugin_url() . '/assets/img/dashboard-users.svg' ?>"
                             alt="<?= __('Commentators', 'anycomment') ?>">

                        <div class="anycomment-dashboard__splitter-half-description">
                            <span><?= AnyComment()->statistics->getCommentorCount() ?></span>
                            <span><?= __('Commentators', 'anycomment') ?></span>
                        </div>
                    </div>
                </div>
                <div class="anycomment-dashboard__splitter-half comments">
                    <div class="anycomment-dashboard__splitter-half-center">
                        <img src="<?= AnyComment()->plugin_url() . '/assets/img/dashboard-comments.svg' ?>"
                             alt="<?= __('All comments', 'anycomment') ?>">
                        <div class="anycomment-dashboard__splitter-half-description">
                            <span><?= AnyComment()->statistics->getApprovedCommentCount() ?></span>
                            <span><?= __('All Comments', 'anycomment') ?></span>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="anycomment-dashboard__statistics">
                <div class="anycomment-dashboard__statistics-graph">
                    <h2><?= __('Overal Statistics', 'anycomment') ?></h2>
                    <?php $data = AnyComment()->statistics->getCommentData(); ?>
                    <canvas id="anycomment-dashboard-chart"></canvas>

                    <script>
                        let chart = setInterval(function () {

                            if ('Chart' in window) {
                                Chart.defaults.global.defaultFontColor = '#c8c8c8';
                                Chart.defaults.global.defaultFontFamily = 'Roboto, Verdana, sans-serif';
                                Chart.defaults.global.defaultFontSize = 18;
                                let c = new Chart(document.getElementById("anycomment-dashboard-chart").getContext('2d'), {
                                    type: 'line',
                                    data: {
                                        labels: <?= $data['label'] ?>,
                                        datasets: [{
                                            label: '<?= __('Comment count', 'anycomment') ?>',
                                            data: <?= $data['data'] ?>,
                                            fill: false,
                                            borderColor: '#ec4568',
                                            borderWidth: 5,
                                            lineTension: 0,
                                            borderJoinStyle: 'miter',
                                            pointRadius: 0,
                                            pointHitRadius: 30,
                                        }]
                                    },
                                    options: {
                                        tooltips: {
                                            borderColor: false,
                                            cornerRadius: 8,
                                            backgroundColor: '#ec4568'
                                        },
                                        layout: {
                                            padding: {
                                                left: 10,
                                                right: 10,
                                                top: 0,
                                                bottom: 0
                                            }
                                        },
                                        scales: {
                                            xAxes: [{
                                                display: false,
                                                gridLines: '#f1f1f1'
                                            }],
                                            yAxes: [{
                                                ticks: {
                                                    beginAtZero: true
                                                }
                                            }]
                                        }
                                    }
                                });

                                clearInterval(chart);
                            }

                            c.canvas.parentNode.style.height = '128px';
                        }, 1000);
                    </script>
                </div>
                <div class="anycomment-dashboard__statistics-userlist">

                </div>
            </div>
        </div>
        <aside class="anycomment-dashboard__sidebar">

        </aside>

        <div class="clearfix"></div>
    </div>
</div>