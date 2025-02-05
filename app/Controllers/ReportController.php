<?php

namespace App\Controllers;

use App\Service\Security;

class ReportController extends AbstractController{
    public $model;
    public $uinModel;
    public $statuses;
    public $security;
    public $indexes;
    public $deadlines;
    public $notificationsModel;
    public $marks;

    public function __construct() {
        $this->model                = new \App\Models\ReportModel;
        $this->uinModel             = new \App\Models\UINModel;
        $this->statuses             = new \App\Models\StatusModel;
        $this->users                = new \App\Models\UserModel;
        $this->indexes              = new \App\Models\IndexModel;
        $this->deadlines            = new \App\Models\DeadlineModel;
        $this->notificationsModel   = new \App\Models\NotificationModel;
        $this->marks                = new \App\Models\MarkModel;
        $this->security             = new \App\Service\Security;
    }

    public function reports() {
        if ($this->security->userHasRole(['ministry_boss']) && isset($_GET['district'])) {
            $districtSlug = $_GET['district'];
        } elseif ($this->security->userHasRole(['district_boss', 'district_staff', 'admin_admin'])) {
            $districtSlug = $this->user()['uin']['slug'];
        } else {
            $districtSlug = false;
        }

        $district = $this->uinModel->findOneBy(['slug' => $districtSlug]);

        if ($district) {
            $reports = [];
            $deadline = $this->deadlines->findOneBy(null,['date' => 'DESC']);
            if ($this->model->findOneBy(['id_deadline' => $deadline['id']])) {
                $lastReportCreated = false;
            } else {
                $lastReportCreated = true;
            }

            foreach ($this->model->findBy(['id_uin' => $district['id']], ['creating' => 'DESC']) as $key => $report) {
                if ($report['staff_ids']) {
                    $staffs = [];
                    $staffCount = 0;

                    foreach (explode(',',$report['staff_ids']) as $staffKey => $staffId) {
                        if ($staffKey == 0 || $staffKey == 1) {
                            $staffs[] = $this->users->findOneBy(['id' => $staffId]);
                        }
                        $staffCount ++;
                    }
                } else {
                    $staffs = null;
                    $staffCount = null;
                }

                $reports[$key] = [
                    'report' => $report,
                    'deadline' => $this->deadlines->findOneBy(['id' => $report['id_deadline']])['date'],
                    'boss' => $this->users->findOneBy(['id' => $report['id_userBoss']]),
                    'staffs' => $staffs,
                    'staffCount' => $staffCount,
                    'status' => $this->statuses->findOneBy(['id' => $report['status']]),
                ];
            }

            $this->render('/staff/report/reports.php', [
                'reports' => $reports,
                'district' => $district,
                'lastReportCreated' => $lastReportCreated
            ]);
        } else {
            $this->security->error('404', 'Такого района не существует');
        }
    }

    public function report() {
        if (isset($_GET['id']) && $this->model->findOneBy(['id' => $_GET['id']])) {
            $report = $this->model->findOneBy(['id' => $_GET['id']]);

            if ($this->user()['id_uin'] === $report['id_uin'] || $this->security->userHasRole(['ministry_boss', 'ministry_staff', 'admin_admin'])) {
                if ($report['staff_ids']) {
                    $staffs = [];
                    foreach (explode(',',$report['staff_ids']) as $staffId) {
                        $staffs[] = $this->users->findOneBy(['id' => $staffId]);
                    }
                } else {
                    $staffs = null;
                }

                $data = [
                    'report' => $report,
                    'deadline' => $this->deadlines->findOneBy(['id' => $report['id_deadline']])['date'],
                    'district' => $this->uinModel->findOneBy(['id' => $report['id_uin']]),
                    'boss' => $this->users->findOneBy(['id' => $report['id_userBoss']]),
                    'staffs' => $staffs,
                    'status' => $this->statuses->findOneBy(['id' => $report['status']])
                ];

                $this->render('/staff/report/report.php', [
                    'data' => $data,
                    'marks' => $this->indexes->reportActions($report['id'])
                ]);
            } else {
                $this->security->error('403', 'У вас недостаточно прав для доступа к данному отчету');
            }
        } else {
            $this->security->error('404', 'Такого отчета не существует');
        }
    }

    public function switchReportStatus() {
        if (isset($_GET['report'])) {
            $report = $this->model->findOneBy(['id' => $_GET['report']]);
            $uin = $this->uinModel->findOneBy(['id' => $report['id_uin']]);

            if ($this->security->userHasRole(['district_boss', 'district_staff']) && $report['status'] == 3) {
                $this->model->update(['status' => 4],['id' => $report['id']]);
                setcookie('alert', json_encode([
                    'message' => '«' . $report['name'] . ' (Район: ' . $uin['owner'] . ')» отправлен на проверку',
                    'type' => 'success'
                ]), time()+1, '/');
            } elseif ($this->security->userHasRole(['ministry_boss', 'ministry_staff']) && isset($_GET['status']) && $this->security->userUIN()['name'] === 'M_ECONOM') {
                if ($_GET['status'] == 1 || $_GET['status'] == 3) {

                    if ($_GET['status'] == 3) {
                        if ($_POST['rejectionReason']) {
                            $this->model->update([
                                'status' => $_GET['status'],
                                'remark' => $report['remark'] + 1,
                                'comment' => $_POST['rejectionReason']
                            ],['id' => $report['id']]);

                            foreach ($this->users->findBy(['id_uin' => $report['id_uin']]) as $user) {
                                $this->notificationsModel->add([
                                    'sender' => $this->user()['id'],
                                    'receiver' => $user['id'],
                                    'datetime' => new \DateTime('now'),
                                    'seen' => 0,
                                    'message' => 'Министерство экономики отправило «' . $report['name'] . '» на доработку по следующей причине: ' . $_POST['rejectionReason']
                                ]);
                            }
                            setcookie('alert', json_encode([
                                'message' => '«' . $report['name'] . ' (Район: ' . $uin['owner'] . ')» отправлен на доработку',
                                'type' => 'success'
                            ]), time()+1, '/');
                        } else {
                            setcookie('alert', json_encode([
                                'message' => 'Вы не указали причину отправки отчета на доработку',
                                'type' => 'error'
                            ]), time()+1, '/');
                        }
                    } else {
                        $deadline = $this->deadlines->findOneBy(['id' => $report['id_deadline']]);
                        if (new \DateTime($deadline['date']) > new \DateTime('now') && !$report['remark']) {
                            $grade = 5;
                        } elseif (new \DateTime($deadline['date']) > new \DateTime('now') && $report['remark'] === 1) {
                            $grade = 4;
                        } elseif (new \DateTime($deadline['date']) > new \DateTime('now') && $report['remark'] > 1) {
                            $grade = 3;
                        } elseif (new \DateTime($deadline['date']) < new \DateTime('now') && !$report['remark']) {
                            $grade = 2;
                        } else {
                            $grade = 1;
                        }

                        $this->model->update(['status' => $_GET['status'], 'grade' => $grade],['id' => $report['id']]);

                        foreach ($this->users->findBy(['id_uin' => $report['id_uin']]) as $user) {
                            $this->notificationsModel->add([
                                'sender' => $this->user()['id'],
                                'receiver' => $user['id'],
                                'datetime' => new \DateTime('now'),
                                'seen' => 0,
                                'message' => 'Министерство экономики утвердило «' . $report['name'] . '»'
                            ]);
                        }
                        setcookie('alert', json_encode([
                            'message' => '«' . $report['name'] . ' (Район: ' . $uin['owner'] . ')» утвержден',
                            'type' => 'success'
                        ]), time()+1, '/');
                    }
                }
            }

            $this->redirectToRoute('/report', [
                'id' => $report['id']
            ]);
        } else {
            $this->redirectToRoute('/');
        }
    }

    public function table() {
        $marks = $this->marks->marksWithoutSV();
        $data = [];

        $currentReport  = $this->model->findOneBy(['id' => $_GET['report']]);
        $yearsQuantity  = $this->indexes->countTableYears($currentReport['id_uin']);
        $years = [];

        for ($i = 0; $i <= $yearsQuantity - 1; $i++) {
            $years[$i] = 2018 + $i;
        }

        foreach ($marks as $key => $mark) {
            $data[$key] = [
                'mark' => $mark['num'],
                'description' => $mark['name'],
                'unit' => $mark['unit'],
                'type' => $mark['type']
            ];

            foreach ($years as $year) {
                $data[$key][$year] = $this->indexes->yearIndexByMarkAndUin($currentReport['id_uin'], $mark['num'], $year);
            }
        }

        $this->render('/staff/report/table.php', [
            'data' => $data,
            'years' => $years,
            'uin' => $this->uinModel->findOneBy(['id' => $currentReport['id_uin']]),
            'report' => $currentReport
        ]);
    }

    public function svTable() {
        $report = $this->model->findOneBy(['id' => $_GET['report']]);
        $alerts = [];

        if ($report['status'] == 3) {
            if ($_POST) {
                if ($this->security->userHasRole(['ministry_boss', 'ministry_staff']) && isset($_POST['marks'])) {
                    foreach ($_POST['marks'] as $mark => $data) {
                        $ministryIndexForThisReport = $this->indexes->oneReportIndexByUinTypeAndMarkNum($mark, $report['id'], 'ministry');
                        if ($data['ministry'] && (float)$data['ministry'] == $data['ministry']) {
                            if ($ministryIndexForThisReport && $ministryIndexForThisReport['index'] !== $data['ministry'] || !$ministryIndexForThisReport) {
                                $this->indexes->add([
                                    'id_user' => $this->user()['id'],
                                    'id_mark' => $mark,
                                    'id_report' => $report['id'],
                                    'id_uin' => $this->user()['id_uin'],
                                    'id_status' => 6,
                                    'index' => $data['ministry'],
                                    'date' => new \DateTime('now')
                                ]);
                                $alerts['successes'][$mark] = 'Индекс успешно изменен';
                            }
                        } elseif ((float)$data['ministry'] != $data['ministry'] && $data['ministry']) {
                            $alerts['errors'][$mark] = 'Индекс введен неправильно';
                        }
                    }
                } elseif ($this->security->userHasRole(['district_boss', 'district_staff']) && isset($_POST['marks'])) {
                    foreach ($_POST['marks'] as $mark => $data) {
                        $action = ($data['action'] === 'agreed') ? 'agreed' : 'disagreed';
                        $result = $data['result'];

                        $ministryIndexForThisReport = $this->indexes->oneReportIndexByUinTypeAndMarkNum($mark, $report['id'], 'ministry',6);
                        $districtIndexForThisReport = $this->indexes->oneReportIndexByUinTypeAndMarkNum($mark, $report['id'], 'district',6);

                        if ($data['district'] && (float)$data['district'] == $data['district']) {
                            if ($result && $action === 'agreed' && !$ministryIndexForThisReport || $result && !$ministryIndexForThisReport || $action === 'agreed' && !$ministryIndexForThisReport) {
                                $alerts['errors'][$mark] = 'Итоговый индекс не может быть согласован, если министерство не вводил данные';
                            } elseif ($action === 'agreed' && !$result) {
                                $alerts['errors'][$mark] = 'Индекс не может быть согласован, если не введен итоговый индекс';
                            } elseif (!$districtIndexForThisReport && $result || !$districtIndexForThisReport && $action === 'agreed') {
                                $alerts['errors'][$mark] = 'Индекс от района должен быть уже введен, перед тем как его согласовать';
                            } elseif ($districtIndexForThisReport && $districtIndexForThisReport['index'] !== $data['district'] || !$districtIndexForThisReport) {
                                ($action == 'agreed') ? $action = 5 : $action = 6;
                                $this->indexes->add([
                                    'id_user' => $this->user()['id'],
                                    'id_mark' => $mark,
                                    'id_report' => $report['id'],
                                    'id_uin' => $this->user()['id_uin'],
                                    'id_status' => $action,
                                    'index' => $data['district'],
                                    'date' => new \DateTime('now')
                                ]);
                                $alerts['successes'][$mark] = 'Индекс успешно изменен';
                            } elseif ($ministryIndexForThisReport && $districtIndexForThisReport && $result && $action === 'agreed') {
                                $agreedIndexForThisReport = $this->indexes->oneReportIndexByUinTypeAndMarkNum($mark, $report['id'], 'district', 5);

                                if (!$agreedIndexForThisReport || $agreedIndexForThisReport['index'] != $result) {
                                    $alerts['successes'][$mark] = 'Итоговый индекс ' . $result .  ' согласован';
                                    $this->indexes->add([
                                        'id_user' => $this->user()['id'],
                                        'id_mark' => $mark,
                                        'id_report' => $report['id'],
                                        'id_uin' => $this->user()['id_uin'],
                                        'id_status' => 5,
                                        'index' => $result,
                                        'date' => new \DateTime('now')
                                    ]);
                                }
                            }
                        } elseif ((float)$data['district'] != $data['district'] && $data['district']) {
                            $alerts['errors'][$mark] = 'Индекс введен неправильно';
                        }
                    }
                }
            }

            $marks = [];

            foreach ($this->marks->marksWithoutSV() as $key => $mark) {
                $ministryIndexForThisReport = $this->indexes->oneReportIndexByUinTypeAndMarkNum($mark['num'], $report['id'], 'ministry',6);
                $districtIndexForThisReport = $this->indexes->oneReportIndexByUinTypeAndMarkNum($mark['num'], $report['id'], 'district',6);
                $agreedIndexForThisReport = $this->indexes->oneReportIndexByUinTypeAndMarkNum($mark['num'], $report['id'], 'district', 5);

                if ($ministryIndexForThisReport) {
                    $mark['ministry'] = $ministryIndexForThisReport['index'];
                }
                if ($districtIndexForThisReport) {
                    $mark['district'] = $districtIndexForThisReport['index'];
                }
                if ($agreedIndexForThisReport) {
                    $mark['result'] = $agreedIndexForThisReport['index'];
                }

                $marks[$key] = $mark;
            }

            $this->render('/staff/report/sv_table.php', [
                'marks' => $marks,
                'uin' => $this->uinModel->findOneBy(['id' => $report['id_uin']]),
                'alerts' => $alerts,
                'report' => $report
            ]);
        } else {
            $this->render('/staff/report/sv_table.php', [
                'uin' => $this->uinModel->findOneBy(['id' => $report['id_uin']]),
                'alerts' => $alerts,
                'report' => $report
            ]);
        }
    }

    public function new() {
        $deadline = $this->deadlines->findOneBy(null,['date' => 'DESC']);

        // Если не существует отчета с последней датой из таблицы deadline то разреши создать отчет
        if (!$this->model->findOneBy(['id_deadline' => $deadline['id']])) {
            $district = $this->uinModel->findOneBy(['id' => $this->user()['uin']['id']]);
            $deadline = $this->deadlines->findOneBy(null,['date' => 'DESC']);

            if ($_POST && $this->formIsValid(['report_staff', 'report_description'])) {
                $staffs = implode(',', $_POST['report_staff']);
                $entries['staff_ids'] = $staffs;
                $entries['description'] = $_POST['report_description'];

                $entries['name'] = $deadline['name'];
                $entries['id_deadline'] = $deadline['id'];
                $entries['creating'] = (new \DateTime('now'));

                $entries['id_userBoss'] = $this->user()['id'];
                $entries['id_uin'] = $this->user()['id_uin'];
                $entries['status'] = 3;

                $this->model->add($entries);
                $this->redirectToRoute('/reports');
            }

            $this->render('/staff/report/form.php', [
                'staffs' => $this->users->findBy(['id_uin' => $this->user()['uin']['id'], 'id_role' => 5]),
                'district' => $district,
                'deadline' => $deadline
            ]);
        } else {
            $this->security->error('404', 'Отчет за последний год уже создан');
        }
    }

    public function edit() {
        if (isset($_GET['report'])) {
            $report = $this->model->findOneBy(['id' => $_GET['report']]);
            $request = file_get_contents('php://input');
            $data = json_decode($request, true);

            if ($report && isset($data['description']) && $this->user()['id_uin'] == $report['id_uin']) {
                $this->model->update(['description' => $data['description']],['id' => $_GET['report']]);
                echo true;
            } else {
                $this->security->error();
            }
        }
    }
}
