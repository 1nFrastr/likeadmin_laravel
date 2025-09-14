<?php

namespace App\Adminapi\Controller;

use App\Adminapi\Logic\WorkbenchLogic;

/**
 * 工作台
 */
class WorkbenchController extends BaseAdminController
{
    /**
     * @notes 工作台
     */
    public function index()
    {
        $result = WorkbenchLogic::index();
        return $this->data($result);
    }

    /**
     * @notes 未完成的工作列表
     */
    public function incompleteWork()
    {
        $result = WorkbenchLogic::incompleteWork();
        return $this->data($result);
    }
}
