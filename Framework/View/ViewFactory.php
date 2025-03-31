protected function getCompiledPath($path)
{
$compiledPath = $this->getCachePath(md5($path) . '.php');

// Kiểm tra xem cache có được bật hay không
if (config('app.view_cache', true) === false) {
// Xóa file cache cũ để buộc biên dịch lại
if (file_exists($compiledPath)) {
unlink($compiledPath);
}
}

return $compiledPath;
}