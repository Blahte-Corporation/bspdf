<?php

namespace BlahteSoftware\BsPdf\Contracts;

interface PdfCore {
    public function setDefaultExtension($defaultExtension);
    public function getDefaultExtension(): string;
    public function setOption($name, $value);
    public function setTimeout($timeout);
    public function setOptions(array $options);
    public function getOptions();
    public function generate($input, $output, array $options = [], $overwrite = false);
    public function generateFromHtml($html, $output, array $options = [], $overwrite = false);
    public function getOutput($input, array $options = []);
    public function getOutputFromHtml($html, array $options = []);
    public function setBinary($binary);
    public function getBinary();
    public function getCommand($input, $output, array $options = []);
    public function removeTemporaryFiles();
    public function getTemporaryFolder();
    public function setTemporaryFolder($temporaryFolder);
    public function resetOptions();
}