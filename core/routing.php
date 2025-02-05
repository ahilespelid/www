<?php
    $GLOBALS['url'] =
    [
    // LEADER //

    // HomeController
    'index'                     =>  ['controller' => 'HomeController', 'action' => 'index'],
    'framework'                 =>  ['controller' => 'HomeController', 'action' => 'framework'],

    // DistrictController
    'district'                  =>  ['controller' => 'DistrictController', 'action' => 'district'],
    'districtReports'           =>  ['controller' => 'DistrictController', 'action' => 'districtJsonReportsByDate'],

    // ProfileController
    'leader'                    =>  ['controller' => 'ProfileController', 'action' => 'leader'],
    'staff'                     =>  ['controller' => 'ProfileController', 'action' => 'staff'],
    'profile'                   =>  ['controller' => 'ProfileController', 'action' => 'profile'],
    'profile/new'               =>  ['controller' => 'ProfileController', 'action' => 'new'],
    'profile/confirm'           =>  ['controller' => 'ProfileController', 'action' => 'confirm'],
    'confirmUser'               =>  ['controller' => 'ProfileController', 'action' => 'confirmUser'],
    'deleteUser'                =>  ['controller' => 'ProfileController', 'action' => 'deleteUser'],
    'profile/fire'              =>  ['controller' => 'ProfileController', 'action' => 'fire'],
    'notifications'             =>  ['controller' => 'ProfileController', 'action' => 'notifications'],
    'districtNotifications'     =>  ['controller' => 'ProfileController', 'action' => 'districtNotificationsJSON'],

    // SupportController
    'support'                   =>  ['controller' => 'SupportController', 'action' => 'support'],
    'messages'                  =>  ['controller' => 'SupportController', 'action' => 'messages'],
    'messages/answers'          =>  ['controller' => 'SupportController', 'action' => 'answers'],
    'rating'                    =>  ['controller' => 'SupportController', 'action' => 'rating'],
    'center'                    =>  ['controller' => 'SupportController', 'action' => 'center'],
    'callCenter'                =>  ['controller' => 'SupportController', 'action' => 'callCenter'],
    'uinData'                   =>  ['controller' => 'SupportController', 'action' => 'uinInJSON'],

    // MarkController
    'generalRating'             =>  ['controller' => 'MarkController', 'action' => 'jsonGeneralRating'],
    'districtRating'            =>  ['controller' => 'MarkController', 'action' => 'jsonDistrictRating'],

    // STAFF //

    // DistrictController
    'districts'                 =>  ['controller' => 'DistrictController', 'action' => 'districts'],

    // ReportController
    'reports'                   =>  ['controller' => 'ReportController', 'action' => 'reports'],
    'report'                    =>  ['controller' => 'ReportController', 'action' => 'report'],
    'report/edit'               =>  ['controller' => 'ReportController', 'action' => 'edit'],
    'report/table'              =>  ['controller' => 'ReportController', 'action' => 'table'],
    'report/sv-table'           =>  ['controller' => 'ReportController', 'action' => 'svTable'],
    'report/new'                =>  ['controller' => 'ReportController', 'action' => 'new'],
    'switchReportStatus'        =>  ['controller' => 'ReportController', 'action' => 'switchReportStatus'],

    // UserController
    'login'                     =>  ['controller' => 'UserController', 'action' => 'login'],

    // ПОКА НЕ ГОТОВЫ //
    'exel'                      =>  ['controller' => 'ExelController', 'action' => 'work'],
    'ajax'                      =>  ['controller' => 'AjaxController', 'action' => 'getMarkData'],
    'calculate'              =>  ['controller' => 'CalculateController', 'action' => 'index'],
    'disk'                      =>  ['controller' => 'DiskController', 'action' => 'index'],
    'disk/up'                 =>  ['controller' => 'DiskController', 'action' => 'upload'],
    ];

    return $GLOBALS['url'];
