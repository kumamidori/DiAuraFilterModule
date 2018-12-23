# Fob.DiAuraFilterModule

- Aura.Filter カスタムバリデータクラスでDIが使えるようにするモジュール
- FormalBears を使用

## Setup

1. バリデータの各サービスクラスはあらかじめ束縛構成済みとする
2. モジュールをインストールする
   
```
$this->install(new DiAuraFilterModule($this->registry));
```

## Configure

Aura/Filter バリデーターとサニタイザーをの構成を設定する
不要な場合は設定無しで良い。

例：
```
di_aura_filter:
  # バリデーター構成
  validate_filters:
    # カスタムルール名にクラスを直接指定する場合
    app.name_in:
      class: 'Fob\DiAuraFilterModuleDemo\Bridge\AuraFilter\NamesValidator'
    # カスタムルール名にサービスコールバックを指定する場合
    app.kuma_spec:
      type: 'callback'
      callback: 'Fob\DiAuraFilterModuleDemo\Hello\KumaSpec'
  # サニタイザー構成
  sanitize_filters:
    app.name_mask:
      class: 'Fob\DiAuraFilterModuleDemo\Bridge\AuraFilter\NameMaskSanitizer'
```

### Usage

```
    /**
     * @var FilterFactory
     */
    private $sFilterFactory;

    /**
     * @Inject
     * @Named("sff=fob.aura_filter.service_filter_factory")
     */
    public function __construct(\Aura\Filter\FilterFactory $sff)
    {
        $this->sFilterFactory= $sff;
    }
```


`DiAuraFilterModule` クラスのコードを参照。

```
        $this->bind(FilterFactory::class)->annotatedWith('fob.aura_filter.service_filter_factory')->toProvider(ServiceFilterFactoryProvider::class);
        $this->bind(SubjectFilter::class)->annotatedWith('fob.aura_filter.service_subject_filter')->toProvider(ServiceSubjectFilterProvider::class);
        $this->bind(ValueFilter::class)->annotatedWith('fob.aura_filter.service_value_filter')->toProvider(ServiceValueFilterProvider::class);
```

## Demo application Links

- [Fob\.DiAuraFilterModuleDemo](https://github.com/kumamidori/Fob.DiAuraFilterModuleDemo)
