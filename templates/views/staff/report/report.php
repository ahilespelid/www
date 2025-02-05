<?php include $this->layout('staff/base/head.php'); ?>

    <body>

        <?php include $this->layout('staff/menu.php'); ?>

        <div class="content">

            <?php include $this->layout('staff/header.php'); ?>

            <div class="body">

                <?php if (isset($_COOKIE['alert'])) { ?>
                    <?php $alert = json_decode($_COOKIE['alert']); ?>
                    <div class="alert alert-<?= $alert->type ?>">
                        <?= $alert->message ?>
                    </div>
                <?php } ?>

                <div class="body__back-button">
                    <?php if ($this->security->userHasRole(['ministry_boss', 'ministry_staff'])) { ?>
                        <a href="/reports?district=<?= $data['district']['slug'] ?>">
                            <span class="icon-expand_left_right body__back__arrow"></span>
                            Вернуться
                        </a>
                    <?php } else { ?>
                        <a href="/reports">
                            <span class="icon-expand_left_right body__back__arrow"></span>
                            Вернуться
                        </a>
                    <?php } ?>

                    <div class="body__back-button__icons">
                        <?php if ($this->security->userHasRole(['ministry_boss', 'ministry_staff'])) { ?>
                            <a href="/center?center=<?= $data['district']['slug'] ?>">
                                <span class="body__back-button__icon">
                                    <span class="icon-trophy"></span>
                                    Рейтинг
                                </span>
                            </a>
                        <?php } ?>
                        <a href="/disk">
                            <span class="body__back-button__icon">
                                <span class="icon-save_light"></span> Диск
                            </span>
                        </a>
                    </div>
                </div>

                <div class="reports-title">
                    <div class="reports-title__my-reports">
                        <div class="reports-title__my-reports__text">
                            <h1 class="district"><?= $data['report']['name'] ?></h1>
                            <span class="title">(Район: <?= $data['district']['owner'] ?>)</span>
                        </div>

                        <div class="reports-title__my-reports__btn">
                        </div>
                    </div>
                </div>

                <div class="reports-body">

                    <div class="reports-body__content">

                        <div class="reports-body__content__main">

                            <div class="reports-body__header">

                                <div class="reports-body__header__buttons">
                                    <?php if ($this->security->userHasRole(['district_boss', 'district_staff']) && $data['report']['status'] == 3) { ?>
                                        <span class="reports-body__header__edit btn">Редактировать</span>
                                    <?php } ?>
                                    <span class="reports-body__header__print btn" onclick="window.print(document);">Печать</span>
                                </div>

                                <div class="reports-body__content__header__download">
                                    <div class="reports-footer__action">
                                        <span>Выгрузить</span>
                                        <span class="icon-arrow_drop_down arrow_icon"></span>

                                        <div class="reports-footer__action__sort none">
                                            <div class="variables excel">
                                                <img width="30" height="30" src="<?= $this->image('/assets/images/staff/xlsx.svg'); ?>">
                                                Выгрузить в Excel
                                            </div>

                                            <div class="variables word">
                                                <img width="30" height="30" src="<?= $this->image('/assets/images/staff/word.svg'); ?>">
                                                Выгрузить в Word
                                            </div>

                                            <div class="variables pdf">
                                                <img width="30" height="30" src="<?= $this->image('/assets/images/staff/pdf.svg'); ?>">
                                                Выгрузить в PDF
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <?php if ($data['report']['comment']) { ?>
                                <div class="reports-body__comment warning">
                                    <p>Комментарий от проверяющего:</p>
                                    <?= $data['report']['comment'] ?>
                                </div>
                            <?php } ?>

                            <div class="reports-body__info">
                                <?= $data['report']['description'] ?>
                            </div>

                            <div class="save__changes none">
                                <button disabled>Сохранить</button>
                                <span class="message"></span>
                            </div>

                        </div>

                        <div class="reports-body__content__activity">

                            <div class="reports-body__content__last-activity">
                                <span>Последняя активность</span>
                            </div>

                            <div class="reports-body__content__changes">

                                <?php include $this->layout('staff/activity.php'); ?>

                            </div>

                        </div>

                    </div>

                    <div class="reports-body__side-block">

                        <?php $status = '';

                            function reportStatus($name) {
                                $statuses = [
                                    'Успешно' => 'green',
                                    'На проверке' => 'orange',
                                    'Просрочен' => 'red',
                                    'В работе' => 'orange',
                                ];

                                echo (isset($statuses[$name])) ? ' ' . $statuses[$name] : '';
                            }

                        ?>

                        <div class="reports-body__side-block__status">
                            <div class="side-block__header<?php reportStatus($data['status']['name']) ?>">
                                <span class="side-block__header__status"><?= $data['status']['name'] ?></span>
                                <span class="side-block__header__date">
                                    <?php if ($data['report']['submitting']) { ?>
                                        <?= (new DateTime($data['report']['submitting']))->format('d.m.o G:i') ?>
                                    <?php } ?>
                                </span>
                            </div>

                            <div class="side-block__body">
                                <ul class="side-block__body__names">
                                    <li class="side-block__body__term-date__title">Крайний срок:</li>
                                    <li class="side-block__body__create-date__title">Создание отчета:</li>
                                    <li class="side-block__body__rating__title">Оценка:</li>
                                </ul>

                                <ul class="side-block__body__values">
                                    <li class="side-block__body__term-date">
                                        <?php if ($data['deadline']) { ?>
                                            <?= (new DateTime($data['deadline']))->format('d.m.o G:i') ?>
                                        <?php } else { ?>
                                            - дата не указана -
                                        <?php } ?>
                                     </li>
                                    <li class="side-block__body__create-date">
                                        <?php if ($data['report']['creating']) { ?>
                                            <?= (new DateTime($data['report']['creating']))->format('d.m.o G:i') ?>
                                        <?php } else { ?>
                                            - дата не указана -
                                        <?php } ?>
                                    </li>
                                    <li class="side-block__body__rating">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <?php if ($i <= $data['report']['grade']) { ?>
                                                <i class="icon-star-fill"></i>
                                            <?php } else { ?>
                                                <i class="icon-star"></i>
                                            <?php } ?>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="reports-body__side-block__table">
                            <div class="side-block__header">Таблица</div>
                            <div class="side-block__body">
                                <span class="icon-darhboard_alt"></span>
                                <a href="/report/table?report=<?= $data['report']['id'] ?>">Смотреть таблицу</a>
                            </div>
                        </div>

                        <div class="reports-body__side-block__responsible">
                            <div class="side-block__header">
                                Ответственный
                            </div>
                            <div class="side-block__body">
                                <img src="<?php if ($data['boss']['avatar']) { echo $data['boss']['avatar']; } else { echo $this->security->setEmptyAvatar(); } ?>" alt="responsible_avatar">
                                <div class="responsible__info">
                                    <span class="responsible__name"><?= $data['boss']['firstname'] . ' ' . $data['boss']['lastname'] ?></span>
                                    <span class="responsible__post">Глава района</span>
                                </div>
                            </div>
                        </div>

                        <div class="reports-body__side-block__assistant">
                            <div class="side-block__header">Сотрудники</div>

                            <?php if ($data['staffs']) { ?>
                                <?php foreach ($data['staffs'] as $staff) { ?>
                                    <div class="side-block__body">
                                        <img src="<?php if ($staff['avatar']) { echo $staff['avatar']; } else { echo $this->security->setEmptyAvatar(); } ?>">
                                        <div class="assistant__info">
                                            <span class="assistant__name"><?= $staff['firstname'] . ' ' . $staff['lastname'] ?></span>
                                            <span class="assistant__post">Районный сотрудник</span>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="side-block__body">
                                    - у отчета нет сотрудников -
                                </div>
                            <?php } ?>
                        </div>

                        <?php if ($this->security->userHasRole(['district_boss', 'district_staff'])) { ?>

                            <?php if ($data['status']['id'] == 3) { ?>
                                <a href="/switchReportStatus?report=<?= $data['report']['id'] ?>&status=4" class="reports-body__side-block__button" id="send_report" onclick="return confirm('Вы уверены что отчет готов и хотите отправить его на проверку?')">
                                    <span class="icon-check" ></span >
                                    Отправить отчет
                                </a>
                            <?php } ?>

                        <?php } elseif ($this->security->userHasRole(['ministry_boss', 'ministry_staff']) && $this->security->userUIN()['name'] === 'M_ECONOM') { ?>

                            <?php if ($data['status']['id'] == 4) { ?>
                                <div class="reports-body__side-block__button" id="accept_report">
                                    <span class="icon-check"></span>
                                    Принять отчет
                                </div>

                                <div class="success__modal none">
                                    <div class="success__modal__header">
                                        <span class="success__modal__header__text">Подтверждение</span>
                                        <span class="success__modal__header__exit"><i class="icon-cross"></i></span>
                                    </div>

                                    <div class="success__modal__body">
                                        <div class="success__modal__body__text">Пожалуйста, подтвердите что вы хотите принять данный отчет:</div>

                                        <a href="/switchReportStatus?report=<?= $data['report']['id'] ?>&status=1" class="success__modal__body__button">
                                            <span class="icon-check"></span>
                                            Принять отчет
                                        </a>
                                    </div>
                                </div>

                                <div class="reports-body__side-block__button" id="finalize_report">
                                    <span class="icon-plus-circle"></span>
                                    Отправить на доработку
                                </div>

                                <div class="fail__modal none">
                                    <div class="fail__modal__header">
                                        <span class="fail__modal__header__text">Подтверждение</span>
                                        <span class="fail__modal__header__exit"><i class="icon-cross"></i></span>
                                    </div>

                                    <div class="fail__modal__body">
                                        <div class="fail__modal__body__text">Пожалуйста, напишите причину по которой вы считаете что задача не выполнена:</div>

                                        <form action="/switchReportStatus?report=<?= $data['report']['id'] ?>&status=3" method="post">
                                            <label>
                                                <textarea placeholder="Начинайте вводить текст..." class="fail__modal__body__textarea" name="rejectionReason"></textarea>
                                            </label>

                                            <div class="fail__modal__footer">
                                                <button type="submit" class="fail_modal__body__button" id='modal_finalize_report'>
                                                    <span class="icon-cross-circle"></span>
                                                    Отправить на доработку
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>

                        <?php } ?>

                    </div>

                </div>

                <div class="revision__modal none">
                    <div class="revision__modal__header">
                        <span class="revision__modal__header__text">Подтверждение</span>
                        <span class="revision__modal__header__exit"><img src="../assets/img/svg/xmark.svg" alt=""></span>
                    </div>
                    <div class="revision__modal__body">
                        <div class="revision__modal__body__text">Убедитесь что следующий комментарий был учтен:</div>
                        <div class="revision__modal__body__comment">
                            В данном отчете не хватает общих данных по региону, таблица к сожалению была заполненна не полностью и я не могу принять такой отчет.
                        </div>
                        <div class="revision__modal__footer">
                            <div class="revision__modal__footer__approval">
                                <div>
                                <input type="checkbox">
                                </div>
                                <span class="revision__modal__footer__approval__text">
                                    Я <span class="name_surname">Грозный Ибрагим</span>, подтверждаю что комментарий проверяющего был учтен и отчет был
                                    доработан в соответствии с указанными ошибками.
                                </span>
                            </div>
                            <button disabled class="revision_modal__body__button" id='modal_send_report_again'>
                                <span class="icon-plus-circle"></span>
                                Отправить на доработку
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </body>

<?php include $this->layout('staff/base/foot.php'); ?>
