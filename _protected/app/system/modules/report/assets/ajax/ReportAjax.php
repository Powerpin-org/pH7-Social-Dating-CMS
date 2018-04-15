<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Report / Asset / Ajax
 */

namespace PH7;

defined('PH7') or exit('Restricted access');

use PH7\Framework\Http\Http;
use PH7\Framework\Mvc\Request\Http as HttpRequest;
use PH7\Framework\Security\CSRF\Token;

class ReportAjax
{
    /** @var HttpRequest */
    private $oHttpRequest;

    /** @var ReportModel */
    private $oReportModel;

    /** @var bool */
    private $bStatus;

    public function __construct()
    {
        if (!(new Token)->check('report')) {
            exit(jsonMsg(0, Form::errorTokenMsg()));
        }

        $this->oHttpRequest = new HttpRequest;
        $this->oReportModel = new ReportModel;

        switch ($this->oHttpRequest->post('type')) {
            case 'delete':
                echo $this->delete();
                break;

            default:
                Http::setHeadersByCode(400);
                exit('Bad Request Error');
        }
    }

    protected function delete()
    {
        $this->bStatus = $this->oReportModel->delete($this->oHttpRequest->post('reportId'));

        if ($this->bStatus) {
            return jsonMsg(1, t('The report has been deleted.'));
        }

        return jsonMsg(0, t('Cannot remove the report. Please try later.'));
    }
}

// Only for Admins
if (AdminCore::auth()) {
    new ReportAjax;
}
