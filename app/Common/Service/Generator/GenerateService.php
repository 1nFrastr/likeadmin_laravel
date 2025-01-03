<?php

namespace App\Common\Service\Generator;

use App\Common\Service\Generator\Core\BaseGenerator;
use App\Common\Service\Generator\Core\ControllerGenerator;
use App\Common\Service\Generator\Core\ListsGenerator;
use App\Common\Service\Generator\Core\LogicGenerator;
use App\Common\Service\Generator\Core\ModelGenerator;
use App\Common\Service\Generator\Core\RouteGenerator;
use App\Common\Service\Generator\Core\SqlGenerator;
use App\Common\Service\Generator\Core\ValidateGenerator;
use App\Common\Service\Generator\Core\VueApiGenerator;
use App\Common\Service\Generator\Core\VueEditGenerator;
use App\Common\Service\Generator\Core\VueIndexGenerator;
use Illuminate\Support\Facades\Cache;

/**
 * 生成器
 */
class GenerateService
{
    // 标记
    protected $flag;

    // 生成文件路径
    protected $generatePath;

    // runtime目录
    protected $runtimePath;

    // 压缩包名称
    protected $zipTempName;

    // 压缩包临时路径
    protected $zipTempPath;

    public function __construct()
    {
        $this->generatePath = storage_path('/') . 'generate/';
        $this->runtimePath = storage_path('/');
    }


    /**
     * @notes 删除生成文件夹内容
     * @author 段誉
     * @date 2022/6/23 18:52
     */
    public function delGenerateDirContent()
    {
        // 删除runtime目录制定文件夹
        !is_dir($this->generatePath) && mkdir($this->generatePath, 0755, true);
        del_target_dir($this->generatePath, false);
    }


    /**
     * @notes 设置生成状态
     * @param $name
     * @param false $status
     */
    public function setGenerateFlag($name, $status = false)
    {
        $this->flag = $name;
        Cache::set($name, (int)$status, 3600);
    }


    /**
     * @notes 获取生成状态标记
     */
    public function getGenerateFlag()
    {
        return Cache::get($this->flag);
    }


    /**
     * @notes 删除标记时间
     * @author 段誉
     * @date 2022/6/23 18:53
     */
    public function delGenerateFlag()
    {
        Cache::delete($this->flag);
    }


    /**
     * @notes 生成器相关类
     * @return string[]
     * @author 段誉
     * @date 2022/6/23 17:17
     */
    public function getGeneratorClass()
    {
        return [
            ControllerGenerator::class,
            ListsGenerator::class,
            ModelGenerator::class,
            ValidateGenerator::class,
            LogicGenerator::class,
            RouteGenerator::class,
            VueApiGenerator::class,
            VueIndexGenerator::class,
            VueEditGenerator::class,
            SqlGenerator::class,
        ];
    }


    /**
     * @notes 生成文件
     * @param array $tableData
     * @author 段誉
     * @date 2022/6/23 18:52
     */
    public function generate(array $tableData)
    {
        foreach ($this->getGeneratorClass() as $item) {
            /**
             * @var BaseGenerator $generator
             */
            $generator = app()->make($item);
            $generator->initGenerateData($tableData);
            $generator->generate();

            // 是否为压缩包下载
            if ($generator->isGenerateTypeZip()) {
                $this->setGenerateFlag($this->flag, true);
            }
            // 是否构建菜单
            if ($generator instanceof SqlGenerator) {
                $generator->isBuildMenu() && $generator->buildMenuHandle();
            }
        }
    }


    /**
     * @notes 预览文件
     * @param array $tableData
     * @return array
     * @author 段誉
     * @date 2022/6/23 18:52
     */
    public function preview(array $tableData)
    {
        $data = [];
        foreach ($this->getGeneratorClass() as $item) {
            /**
             * @var BaseGenerator $generator
             */
            $generator = app()->make($item);
            $generator->initGenerateData($tableData);
            $data[] = $generator->fileInfo();
        }
        return $data;
    }


    /**
     * @notes 压缩文件
     * @author 段誉
     * @date 2022/6/23 19:02
     */
    public function zipFile()
    {
        $fileName = 'curd-' . date('YmdHis') . '.zip';
        $this->zipTempName = $fileName;
        $this->zipTempPath = $this->generatePath . $fileName;
        $zip = new \ZipArchive();
        $zip->open($this->zipTempPath, \ZipArchive::CREATE);
        $this->addFileZip($this->runtimePath, 'generate', $zip);
        $zip->close();
    }


    /**
     * @notes 往压缩包写入文件
     * @param $basePath
     * @param $dirName
     * @param $zip
     * @author 段誉
     * @date 2022/6/23 19:02
     */
    public function addFileZip($basePath, $dirName, $zip)
    {
        $handler = opendir($basePath . $dirName);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != '.' && $filename != '..') {
                if (is_dir($basePath . $dirName . '/' . $filename)) {
                    // 当前路径是文件夹
                    $this->addFileZip($basePath, $dirName . '/' . $filename, $zip);
                } else {
                    // 写入文件到压缩包
                    $zip->addFile($basePath . $dirName . '/' . $filename, $dirName . '/' . $filename);
                }
            }
        }
        closedir($handler);
    }


    /**
     * @notes 返回压缩包临时路径
     * @return mixed
     * @author 段誉
     * @date 2022/6/24 9:41
     */
    public function getDownloadUrl()
    {
        $vars = ['file' => $this->zipTempName];
        Cache::set('curd_file_name' . $this->zipTempName, $this->zipTempName, 3600);
        return url()->query("adminapi/tools.generator/download", $vars);
    }

}
