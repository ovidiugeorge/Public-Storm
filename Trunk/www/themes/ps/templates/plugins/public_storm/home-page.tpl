<div id="intro">
	<div class="table">
		<div class="table-row">
			<div class="table-cell _30 title">
				<h3>{t}intro_accroche{/t}
					<ul>
					{foreach from=$storms item=storm name=liste}
						<li><a href="{$storm.url}">{$storm.root|ucfirst}</a> <small class="author">({t}by{/t} <a href="{$base_url}/utilisateurs/{$storm.author_login}/">{$storm.author}</a>)</small></li>
					{/foreach}
						<li><a href="{$base_url}/storms/">...</a></li>
					</ul>
				</h3>
			</div>
			
			<div class="table-cell _100 right" id="screen">
				<div id="menu" class="intro">
					<ol class="navigation">
						<li><a href="#tab1">{t}intro_title1{/t}</a></li>
						<li><a href="#tab2">{t}intro_title2{/t}</a></li>
						<li><a href="#tab3">{t}intro_title3{/t}</a></li>
					</ol>
				</div>
				<div class="clearboth"></div>
				<div class="scroll">
					<div class="scrollContainer">
						<div id="tab1" class="panel">
							<img src="{$theme_dir}img/weather-storm-320.png" title="{t}intro_title1{/t}" alt="{t}intro_title1{/t}" />
							<h4>{t}intro_title1{/t}</h4>
							<p>{t}intro_p1{/t}</p>
							<p><a href="#tab2">&gt;&gt;</a></p>
						</div>
						<div id="tab2" class="panel">
							<img src="{$theme_dir}img/weather-storm-320.png" title="{t}intro_title2{/t}" alt="{t}intro_title2{/t}" />
							<h4>{t}intro_title2{/t}</h4>
							<p>{t}intro_p2{/t}</p>
							<p><a href="#tab1">&lt;&lt;</a> <a href="#tab3">&gt;&gt;</a></p>
						</div>
						<div id="tab3" class="panel">
							<img src="{$theme_dir}img/weather-storm-320.png" title="{t}intro_title3{/t}" alt="{t}intro_title3{/t}" />
							<h4>{t}intro_title3{/t}</h4>
							<p>{t}intro_p3{/t}</p>
							<p><a href="#tab2">&lt;&lt;</a></p>
						</div>
					</div><!-- //scrollContainer -->
				</div><!-- //scroll -->
			</div>
		</div>
	</div>
</div><!-- intro -->