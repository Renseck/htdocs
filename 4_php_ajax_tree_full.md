```
4_php_ajax\
├── assets\
│   ├── css\
│   │   └── mystyle.css
│   ├── db\
│   │   └── users.sql
│   ├── images\
│   │   └── placeholder.png
│   └── js\
│       ├── ajax.js
│       └── rating.js
├── classes\
│   ├── config\
│   │   ├── pageconfig.php
│   │   └── userconfig.php
│   ├── controller\
│   │   ├── ajaxcontroller.php
│   │   ├── authcontroller.php
│   │   ├── cartcontroller.php
│   │   ├── garblecontroller.php
│   │   ├── maincontroller.php
│   │   └── sessioncontroller.php
│   ├── database\
│   │   ├── crudoperations.php
│   │   └── databaseconnection.php
│   ├── model\
│   │   ├── ordermodel.php
│   │   ├── productmodel.php
│   │   ├── ratingmodel.php
│   │   └── usermodel.php
│   ├── utils\
│   │   └── validator.php
│   └── view\
│       ├── aboutpage.php
│       ├── cartpage.php
│       ├── confirmationpage.php
│       ├── contactpage.php
│       ├── homepage.php
│       ├── htmldocument.php
│       ├── loginpage.php
│       ├── logoutpage.php
│       ├── productpage.php
│       ├── registerpage.php
│       └── webshoppage.php
├── includes\
│   └── autoload.php
├── src\
├── tests\
│   ├── modeltests\
│   │   ├── ordermodeltest.php
│   │   ├── productmodeltest.php
│   │   └── usermodeltest.php
│   ├── pagetests\
│   │   ├── aboutpagetest.php
│   │   ├── cartpagetest.php
│   │   ├── confirmationpagetest.php
│   │   ├── contactpagetest.php
│   │   ├── homepagetest.php
│   │   ├── loginpagetest.php
│   │   ├── logoutpagetest.php
│   │   ├── productpagetest.php
│   │   ├── registerpagetest.php
│   │   └── webshoppagetest.php
│   ├── runtests.php
│   └── testconfig.php
├── vendor\
│   ├── composer\
│   │   ├── autoload_classmap.php
│   │   ├── autoload_files.php
│   │   ├── autoload_namespaces.php
│   │   ├── autoload_psr4.php
│   │   ├── autoload_real.php
│   │   ├── autoload_static.php
│   │   ├── ClassLoader.php
│   │   ├── installed.json
│   │   ├── installed.php
│   │   ├── InstalledVersions.php
│   │   ├── LICENSE
│   │   └── platform_check.php
│   ├── graham-campbell\
│   │   └── result-type\
│   │       ├── .git\
│   │       │   ├── hooks\
│   │       │   │   ├── applypatch-msg.sample
│   │       │   │   ├── commit-msg.sample
│   │       │   │   ├── fsmonitor-watchman.sample
│   │       │   │   ├── post-update.sample
│   │       │   │   ├── pre-applypatch.sample
│   │       │   │   ├── pre-commit.sample
│   │       │   │   ├── pre-merge-commit.sample
│   │       │   │   ├── pre-push.sample
│   │       │   │   ├── pre-rebase.sample
│   │       │   │   ├── pre-receive.sample
│   │       │   │   ├── prepare-commit-msg.sample
│   │       │   │   ├── push-to-checkout.sample
│   │       │   │   └── update.sample
│   │       │   ├── info\
│   │       │   │   ├── exclude
│   │       │   │   └── refs
│   │       │   ├── logs\
│   │       │   │   ├── refs\
│   │       │   │   │   ├── heads\
│   │       │   │   │   │   └── 1.1
│   │       │   │   │   └── remotes\
│   │       │   │   │       └── origin\
│   │       │   │   │           └── HEAD
│   │       │   │   └── HEAD
│   │       │   ├── objects\
│   │       │   │   ├── info\
│   │       │   │   │   └── packs
│   │       │   │   └── pack\
│   │       │   │       ├── pack-3e0c02a1707083949880b8e20490afc4501b9aff.idx
│   │       │   │       └── pack-3e0c02a1707083949880b8e20490afc4501b9aff.pack
│   │       │   ├── refs\
│   │       │   │   ├── heads\
│   │       │   │   │   └── 1.1
│   │       │   │   ├── remotes\
│   │       │   │   │   └── origin\
│   │       │   │   │       └── HEAD
│   │       │   │   └── tags\
│   │       │   ├── config
│   │       │   ├── description
│   │       │   ├── HEAD
│   │       │   ├── index
│   │       │   ├── ORIG_HEAD
│   │       │   └── packed-refs
│   │       ├── .github\
│   │       │   ├── workflows\
│   │       │   │   └── tests.yml
│   │       │   ├── CODE_OF_CONDUCT.md
│   │       │   ├── CONTRIBUTING.md
│   │       │   ├── FUNDING.yml
│   │       │   └── SECURITY.md
│   │       ├── src\
│   │       │   ├── Error.php
│   │       │   ├── Result.php
│   │       │   └── Success.php
│   │       ├── tests\
│   │       │   └── ResultTest.php
│   │       ├── .gitattributes
│   │       ├── .gitignore
│   │       ├── CHANGELOG.md
│   │       ├── composer.json
│   │       ├── LICENSE
│   │       ├── phpunit.xml.dist
│   │       └── README.md
│   ├── phpoption\
│   │   └── phpoption\
│   │       ├── .git\
│   │       │   ├── hooks\
│   │       │   │   ├── applypatch-msg.sample
│   │       │   │   ├── commit-msg.sample
│   │       │   │   ├── fsmonitor-watchman.sample
│   │       │   │   ├── post-update.sample
│   │       │   │   ├── pre-applypatch.sample
│   │       │   │   ├── pre-commit.sample
│   │       │   │   ├── pre-merge-commit.sample
│   │       │   │   ├── pre-push.sample
│   │       │   │   ├── pre-rebase.sample
│   │       │   │   ├── pre-receive.sample
│   │       │   │   ├── prepare-commit-msg.sample
│   │       │   │   ├── push-to-checkout.sample
│   │       │   │   └── update.sample
│   │       │   ├── info\
│   │       │   │   ├── exclude
│   │       │   │   └── refs
│   │       │   ├── logs\
│   │       │   │   ├── refs\
│   │       │   │   │   ├── heads\
│   │       │   │   │   │   └── master
│   │       │   │   │   └── remotes\
│   │       │   │   │       └── origin\
│   │       │   │   │           └── HEAD
│   │       │   │   └── HEAD
│   │       │   ├── objects\
│   │       │   │   ├── info\
│   │       │   │   │   └── packs
│   │       │   │   └── pack\
│   │       │   │       ├── pack-6e1d13a4c6b2cbadaa467e8e64a2b5d4f6c6e223.idx
│   │       │   │       └── pack-6e1d13a4c6b2cbadaa467e8e64a2b5d4f6c6e223.pack
│   │       │   ├── refs\
│   │       │   │   ├── heads\
│   │       │   │   │   └── master
│   │       │   │   ├── remotes\
│   │       │   │   │   └── origin\
│   │       │   │   │       └── HEAD
│   │       │   │   └── tags\
│   │       │   ├── config
│   │       │   ├── description
│   │       │   ├── HEAD
│   │       │   ├── index
│   │       │   ├── ORIG_HEAD
│   │       │   └── packed-refs
│   │       ├── .github\
│   │       │   ├── workflows\
│   │       │   │   ├── static.yml
│   │       │   │   └── tests.yml
│   │       │   ├── CODE_OF_CONDUCT.md
│   │       │   ├── CONTRIBUTING.md
│   │       │   ├── FUNDING.yml
│   │       │   └── SECURITY.md
│   │       ├── src\
│   │       │   └── PhpOption\
│   │       │       ├── LazyOption.php
│   │       │       ├── None.php
│   │       │       ├── Option.php
│   │       │       └── Some.php
│   │       ├── tests\
│   │       │   ├── PhpOption\
│   │       │   │   └── Tests\
│   │       │   │       ├── EnsureTest.php
│   │       │   │       ├── LazyOptionTest.php
│   │       │   │       ├── NoneTest.php
│   │       │   │       ├── OptionTest.php
│   │       │   │       └── SomeTest.php
│   │       │   └── bootstrap.php
│   │       ├── vendor-bin\
│   │       │   ├── phpstan\
│   │       │   │   └── composer.json
│   │       │   └── psalm\
│   │       │       └── composer.json
│   │       ├── .gitattributes
│   │       ├── .gitignore
│   │       ├── composer.json
│   │       ├── LICENSE
│   │       ├── Makefile
│   │       ├── phpstan-baseline.neon
│   │       ├── phpstan.neon.dist
│   │       ├── phpunit.xml.dist
│   │       ├── psalm-baseline.xml
│   │       ├── psalm.xml
│   │       └── README.md
│   ├── symfony\
│   │   ├── polyfill-ctype\
│   │   │   ├── .git\
│   │   │   │   ├── hooks\
│   │   │   │   │   ├── applypatch-msg.sample
│   │   │   │   │   ├── commit-msg.sample
│   │   │   │   │   ├── fsmonitor-watchman.sample
│   │   │   │   │   ├── post-update.sample
│   │   │   │   │   ├── pre-applypatch.sample
│   │   │   │   │   ├── pre-commit.sample
│   │   │   │   │   ├── pre-merge-commit.sample
│   │   │   │   │   ├── pre-push.sample
│   │   │   │   │   ├── pre-rebase.sample
│   │   │   │   │   ├── pre-receive.sample
│   │   │   │   │   ├── prepare-commit-msg.sample
│   │   │   │   │   ├── push-to-checkout.sample
│   │   │   │   │   └── update.sample
│   │   │   │   ├── info\
│   │   │   │   │   ├── exclude
│   │   │   │   │   └── refs
│   │   │   │   ├── logs\
│   │   │   │   │   ├── refs\
│   │   │   │   │   │   ├── heads\
│   │   │   │   │   │   │   └── 1.x
│   │   │   │   │   │   └── remotes\
│   │   │   │   │   │       └── origin\
│   │   │   │   │   │           └── HEAD
│   │   │   │   │   └── HEAD
│   │   │   │   ├── objects\
│   │   │   │   │   ├── info\
│   │   │   │   │   │   └── packs
│   │   │   │   │   └── pack\
│   │   │   │   │       ├── pack-27d40a477c4b17cafbb75f7b18d26eab2b8d781d.idx
│   │   │   │   │       └── pack-27d40a477c4b17cafbb75f7b18d26eab2b8d781d.pack
│   │   │   │   ├── refs\
│   │   │   │   │   ├── heads\
│   │   │   │   │   │   └── 1.x
│   │   │   │   │   ├── remotes\
│   │   │   │   │   │   └── origin\
│   │   │   │   │   │       └── HEAD
│   │   │   │   │   └── tags\
│   │   │   │   ├── config
│   │   │   │   ├── description
│   │   │   │   ├── HEAD
│   │   │   │   ├── index
│   │   │   │   ├── ORIG_HEAD
│   │   │   │   └── packed-refs
│   │   │   ├── bootstrap.php
│   │   │   ├── bootstrap80.php
│   │   │   ├── composer.json
│   │   │   ├── Ctype.php
│   │   │   ├── LICENSE
│   │   │   └── README.md
│   │   ├── polyfill-mbstring\
│   │   │   ├── .git\
│   │   │   │   ├── hooks\
│   │   │   │   │   ├── applypatch-msg.sample
│   │   │   │   │   ├── commit-msg.sample
│   │   │   │   │   ├── fsmonitor-watchman.sample
│   │   │   │   │   ├── post-update.sample
│   │   │   │   │   ├── pre-applypatch.sample
│   │   │   │   │   ├── pre-commit.sample
│   │   │   │   │   ├── pre-merge-commit.sample
│   │   │   │   │   ├── pre-push.sample
│   │   │   │   │   ├── pre-rebase.sample
│   │   │   │   │   ├── pre-receive.sample
│   │   │   │   │   ├── prepare-commit-msg.sample
│   │   │   │   │   ├── push-to-checkout.sample
│   │   │   │   │   └── update.sample
│   │   │   │   ├── info\
│   │   │   │   │   ├── exclude
│   │   │   │   │   └── refs
│   │   │   │   ├── logs\
│   │   │   │   │   ├── refs\
│   │   │   │   │   │   ├── heads\
│   │   │   │   │   │   │   └── 1.x
│   │   │   │   │   │   └── remotes\
│   │   │   │   │   │       └── origin\
│   │   │   │   │   │           └── HEAD
│   │   │   │   │   └── HEAD
│   │   │   │   ├── objects\
│   │   │   │   │   ├── info\
│   │   │   │   │   │   └── packs
│   │   │   │   │   └── pack\
│   │   │   │   │       ├── pack-a64a494f5095e287a2dba724c498183ad2a27e96.idx
│   │   │   │   │       └── pack-a64a494f5095e287a2dba724c498183ad2a27e96.pack
│   │   │   │   ├── refs\
│   │   │   │   │   ├── heads\
│   │   │   │   │   │   └── 1.x
│   │   │   │   │   ├── remotes\
│   │   │   │   │   │   └── origin\
│   │   │   │   │   │       └── HEAD
│   │   │   │   │   └── tags\
│   │   │   │   ├── config
│   │   │   │   ├── description
│   │   │   │   ├── HEAD
│   │   │   │   ├── index
│   │   │   │   ├── ORIG_HEAD
│   │   │   │   └── packed-refs
│   │   │   ├── Resources\
│   │   │   │   └── unidata\
│   │   │   │       ├── caseFolding.php
│   │   │   │       ├── lowerCase.php
│   │   │   │       ├── titleCaseRegexp.php
│   │   │   │       └── upperCase.php
│   │   │   ├── bootstrap.php
│   │   │   ├── bootstrap80.php
│   │   │   ├── composer.json
│   │   │   ├── LICENSE
│   │   │   ├── Mbstring.php
│   │   │   └── README.md
│   │   └── polyfill-php80\
│   │       ├── .git\
│   │       │   ├── hooks\
│   │       │   │   ├── applypatch-msg.sample
│   │       │   │   ├── commit-msg.sample
│   │       │   │   ├── fsmonitor-watchman.sample
│   │       │   │   ├── post-update.sample
│   │       │   │   ├── pre-applypatch.sample
│   │       │   │   ├── pre-commit.sample
│   │       │   │   ├── pre-merge-commit.sample
│   │       │   │   ├── pre-push.sample
│   │       │   │   ├── pre-rebase.sample
│   │       │   │   ├── pre-receive.sample
│   │       │   │   ├── prepare-commit-msg.sample
│   │       │   │   ├── push-to-checkout.sample
│   │       │   │   └── update.sample
│   │       │   ├── info\
│   │       │   │   ├── exclude
│   │       │   │   └── refs
│   │       │   ├── logs\
│   │       │   │   ├── refs\
│   │       │   │   │   ├── heads\
│   │       │   │   │   │   └── 1.x
│   │       │   │   │   └── remotes\
│   │       │   │   │       └── origin\
│   │       │   │   │           └── HEAD
│   │       │   │   └── HEAD
│   │       │   ├── objects\
│   │       │   │   ├── info\
│   │       │   │   │   └── packs
│   │       │   │   └── pack\
│   │       │   │       ├── pack-51996547ee790b96e9234dfc86fbde9ef577c494.idx
│   │       │   │       └── pack-51996547ee790b96e9234dfc86fbde9ef577c494.pack
│   │       │   ├── refs\
│   │       │   │   ├── heads\
│   │       │   │   │   └── 1.x
│   │       │   │   ├── remotes\
│   │       │   │   │   └── origin\
│   │       │   │   │       └── HEAD
│   │       │   │   └── tags\
│   │       │   ├── config
│   │       │   ├── description
│   │       │   ├── HEAD
│   │       │   ├── index
│   │       │   ├── ORIG_HEAD
│   │       │   └── packed-refs
│   │       ├── Resources\
│   │       │   └── stubs\
│   │       │       ├── Attribute.php
│   │       │       ├── PhpToken.php
│   │       │       ├── Stringable.php
│   │       │       ├── UnhandledMatchError.php
│   │       │       └── ValueError.php
│   │       ├── bootstrap.php
│   │       ├── composer.json
│   │       ├── LICENSE
│   │       ├── Php80.php
│   │       ├── PhpToken.php
│   │       └── README.md
│   ├── vlucas\
│   │   └── phpdotenv\
│   │       ├── .git\
│   │       │   ├── hooks\
│   │       │   │   ├── applypatch-msg.sample
│   │       │   │   ├── commit-msg.sample
│   │       │   │   ├── fsmonitor-watchman.sample
│   │       │   │   ├── post-update.sample
│   │       │   │   ├── pre-applypatch.sample
│   │       │   │   ├── pre-commit.sample
│   │       │   │   ├── pre-merge-commit.sample
│   │       │   │   ├── pre-push.sample
│   │       │   │   ├── pre-rebase.sample
│   │       │   │   ├── pre-receive.sample
│   │       │   │   ├── prepare-commit-msg.sample
│   │       │   │   ├── push-to-checkout.sample
│   │       │   │   └── update.sample
│   │       │   ├── info\
│   │       │   │   ├── exclude
│   │       │   │   └── refs
│   │       │   ├── logs\
│   │       │   │   ├── refs\
│   │       │   │   │   ├── heads\
│   │       │   │   │   │   └── master
│   │       │   │   │   └── remotes\
│   │       │   │   │       └── origin\
│   │       │   │   │           └── HEAD
│   │       │   │   └── HEAD
│   │       │   ├── objects\
│   │       │   │   ├── info\
│   │       │   │   │   └── packs
│   │       │   │   └── pack\
│   │       │   │       ├── pack-549b7d07fbf93a8812219d97a44623ef98584ed0.idx
│   │       │   │       └── pack-549b7d07fbf93a8812219d97a44623ef98584ed0.pack
│   │       │   ├── refs\
│   │       │   │   ├── heads\
│   │       │   │   │   └── master
│   │       │   │   ├── remotes\
│   │       │   │   │   └── origin\
│   │       │   │   │       └── HEAD
│   │       │   │   └── tags\
│   │       │   ├── config
│   │       │   ├── description
│   │       │   ├── HEAD
│   │       │   ├── index
│   │       │   ├── ORIG_HEAD
│   │       │   └── packed-refs
│   │       ├── .github\
│   │       │   ├── workflows\
│   │       │   │   ├── static.yml
│   │       │   │   └── tests.yml
│   │       │   ├── CODE_OF_CONDUCT.md
│   │       │   ├── CONTRIBUTING.md
│   │       │   ├── FUNDING.yml
│   │       │   └── SECURITY.md
│   │       ├── src\
│   │       │   ├── Exception\
│   │       │   │   ├── ExceptionInterface.php
│   │       │   │   ├── InvalidEncodingException.php
│   │       │   │   ├── InvalidFileException.php
│   │       │   │   ├── InvalidPathException.php
│   │       │   │   └── ValidationException.php
│   │       │   ├── Loader\
│   │       │   │   ├── Loader.php
│   │       │   │   ├── LoaderInterface.php
│   │       │   │   └── Resolver.php
│   │       │   ├── Parser\
│   │       │   │   ├── Entry.php
│   │       │   │   ├── EntryParser.php
│   │       │   │   ├── Lexer.php
│   │       │   │   ├── Lines.php
│   │       │   │   ├── Parser.php
│   │       │   │   ├── ParserInterface.php
│   │       │   │   └── Value.php
│   │       │   ├── Repository\
│   │       │   │   ├── Adapter\
│   │       │   │   │   ├── AdapterInterface.php
│   │       │   │   │   ├── ApacheAdapter.php
│   │       │   │   │   ├── ArrayAdapter.php
│   │       │   │   │   ├── EnvConstAdapter.php
│   │       │   │   │   ├── GuardedWriter.php
│   │       │   │   │   ├── ImmutableWriter.php
│   │       │   │   │   ├── MultiReader.php
│   │       │   │   │   ├── MultiWriter.php
│   │       │   │   │   ├── PutenvAdapter.php
│   │       │   │   │   ├── ReaderInterface.php
│   │       │   │   │   ├── ReplacingWriter.php
│   │       │   │   │   ├── ServerConstAdapter.php
│   │       │   │   │   └── WriterInterface.php
│   │       │   │   ├── AdapterRepository.php
│   │       │   │   ├── RepositoryBuilder.php
│   │       │   │   └── RepositoryInterface.php
│   │       │   ├── Store\
│   │       │   │   ├── File\
│   │       │   │   │   ├── Paths.php
│   │       │   │   │   └── Reader.php
│   │       │   │   ├── FileStore.php
│   │       │   │   ├── StoreBuilder.php
│   │       │   │   ├── StoreInterface.php
│   │       │   │   └── StringStore.php
│   │       │   ├── Util\
│   │       │   │   ├── Regex.php
│   │       │   │   └── Str.php
│   │       │   ├── Dotenv.php
│   │       │   └── Validator.php
│   │       ├── tests\
│   │       │   ├── Dotenv\
│   │       │   │   ├── Loader\
│   │       │   │   │   └── LoaderTest.php
│   │       │   │   ├── Parser\
│   │       │   │   │   ├── EntryParserTest.php
│   │       │   │   │   ├── LexerTest.php
│   │       │   │   │   ├── LinesTest.php
│   │       │   │   │   └── ParserTest.php
│   │       │   │   ├── Repository\
│   │       │   │   │   ├── Adapter\
│   │       │   │   │   │   ├── ArrayAdapterTest.php
│   │       │   │   │   │   ├── EnvConstAdapterTest.php
│   │       │   │   │   │   ├── PutenvAdapterTest.php
│   │       │   │   │   │   └── ServerConstAdapterTest.php
│   │       │   │   │   └── RepositoryTest.php
│   │       │   │   ├── Store\
│   │       │   │   │   └── StoreTest.php
│   │       │   │   ├── DotenvTest.php
│   │       │   │   └── ValidatorTest.php
│   │       │   └── fixtures\
│   │       │       └── env\
│   │       │           ├── .env
│   │       │           ├── assertions.env
│   │       │           ├── booleans.env
│   │       │           ├── commented.env
│   │       │           ├── empty.env
│   │       │           ├── example.env
│   │       │           ├── exported.env
│   │       │           ├── immutable.env
│   │       │           ├── integers.env
│   │       │           ├── large.env
│   │       │           ├── multibyte.env
│   │       │           ├── multiline.env
│   │       │           ├── multiple.env
│   │       │           ├── mutable.env
│   │       │           ├── nested.env
│   │       │           ├── quoted.env
│   │       │           ├── specialchars.env
│   │       │           ├── unicodevarnames.env
│   │       │           ├── utf8-with-bom-encoding.env
│   │       │           └── windows.env
│   │       ├── vendor-bin\
│   │       │   ├── phpstan\
│   │       │   │   └── composer.json
│   │       │   └── psalm\
│   │       │       └── composer.json
│   │       ├── .editorconfig
│   │       ├── .gitattributes
│   │       ├── .gitignore
│   │       ├── composer.json
│   │       ├── LICENSE
│   │       ├── Makefile
│   │       ├── phpstan-baseline.neon
│   │       ├── phpstan.neon.dist
│   │       ├── phpunit.xml.dist
│   │       ├── psalm-baseline.xml
│   │       ├── psalm.xml
│   │       ├── README.md
│   │       └── UPGRADING.md
│   └── autoload.php
├── .env
├── composer.json
├── composer.lock
├── index.php
├── README.md
└── test.php
```
