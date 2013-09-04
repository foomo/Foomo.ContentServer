<?php

//header('Content-Type: text/plain');

\Foomo\Services\Reflection\ServiceObjectType::$seriouslyCacheInDevAndTestMode = true;

$config = new Foomo\ContentServer\DomainConfig();
$config->server = 'tcp://192.168.56.1:8081';
ini_set('xdebug.var_display_max_depth', '10');

\Foomo\Timer::start('get proxy');
$tcpProxy = $config->getProxy();
\Foomo\Timer::stop('get proxy');
\Foomo\Timer::start('effin all');
for($i = 0; $i < 1000; $i++) {
	\Foomo\Timer::start('getURI');
	var_dump($tcpProxy->getURI($region = 'universe', $language = 'de', '/home'));
	/*
	var_dump($tcpProxy->getContent(
		\Foomo\ContentServer\Vo\Requests\Content::create('/de/projects/apps', array('www'))
			->addNode('main', '/', array('text/html'), true)
	));
	*/
	\Foomo\Timer::stop('getURI');
}
\Foomo\Timer::stop('effin all');
//var_dump($tcpProxy, $tcpProxy->getURI($region = 'universe', $language = 'de', '/home'));

echo '<plaintext>' . \Foomo\Timer::getStats();


exit;

$config = new Foomo\ContentServer\DomainConfig();
$request = Foomo\ContentServer\Vo\Requests\Content::create('/de/projects/apps', array('www'))
	->addNode('main', '6666cd76f96956469e7be39d750cc7d9', array('text/html'), true)
;
var_dump($request);

\Foomo\Timer::start($topic = 'contentserver');
for($i=0; $i<1; $i++) {
	$rawContent = $content = $config->getProxy()->getContent($request);
}
\Foomo\Timer::stop($topic);

// \Foomo\Services\Reflection\ServiceObjectType::$seriouslyCacheInDevAndTestMode = true;

var_dump($content);

for($i=0; $i<5; $i++) {
	\Foomo\Timer::start("map");
	$content = \Foomo\SimpleData\VoMapper::map($rawContent, new \Foomo\ContentServer\Vo\Content\SiteContent());
	\Foomo\Timer::stop("map");
}

var_dump($content);

echo '<pre>' . \Foomo\Timer::getStats() . '</pre>';
//70e1733efe56e20a6edcb96632119b4b