<?xml version="1.0" encoding="UTF-8"?>
{setlocale type="all" locale="fr_FR.utf8"}
<rss version="2.0">
	<channel>
		<title>{$title}, {t}baseline{t}</title>
		<link>{$base_url_http}</link>
		<description>{$site_baseline} {t}description{/t}</description>
		<language>fr-fr</language>
		<pubDate>{$date}</pubDate>
		<lastBuildDate>{$date}</lastBuildDate>
		<generator>{$rss_generator}</generator>
		<managingEditor>{$rss_managingeditor}</managingEditor>
		<webMaster>{$rss_webmaster}</webMaster>
		<ttl>60</ttl>
		<image>
			<title>{$title}</title>
			<url>{$site_theme}img/logo.jpg</url>
			<link>{$base_url_http}</link>
			<width>73</width>
			<height>70</height>
			<description>{$site_baseline}</description>
		</image>
	</channel>
	{foreach from=$storms item=storm}
	{if $storm.root ne ""}
	<item>
		<title>{$storm.root|ucfirst}</title>
		<author>{$storm.author}</author>
		<link>{$base_url_http}/storm/{$storm.permaname|url}/</link>
		<description><![CDATA[{assign var=rootCap value=$storm.root|ucfirst}{$i18n.suggest_it|sprintf:$rootCap} <a href="{$base_url_http}/storm/{$storm.permaname|url}/">{$storm.root|ucfirst}]]></description>
		<guid>{$name.name}-{$smarty.now}</guid>
		<pubDate>{$storm.date|date_format:"%a, %d %B %Y %R:%M:%S GMT"}</pubDate>
		<source>{$base_url_http}/backend/rss.php</source>
		<enclosure url="{$site_theme}img/lightning.png" length="692" type="image/x-png" />
	</item>
	{/if}
	{/foreach}
</rss>