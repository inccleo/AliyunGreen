<h1 align="center"> 阿里云内容安全 </h1>

## Installing

```shell
$ composer require leo/green -vvv
```

## 引用

```
use Leo\Green\Green;
$obj = new Green('accessKeyId','accessKeySecret');
```


## 文本内容检测

```
$msg = $obj->textScan("妈*");
```

## 图片同步内容检测

```
print_r($obj->imageScan($url));
```

## 视频检测

```
print_r($obj->VideoScan('视频url'));

```

##  查询视频异测结果

```
print_r($obj->VideoResults('taskId'));
```


## Usage

TODO

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/leo/green/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/leo/green/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT