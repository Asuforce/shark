<?php $this->setLayoutVar('title', 'Search') ?>

<link rel="stylesheet"type="text/css" href="<?php echo $base_url; ?>/css/search.css"title="top">
<script type="text/javascript" src="<?php echo $base_url; ?>/js/search.js"></script>

<!-- サーチ -->
<paper-shadow class="search_user" z="1">
	<form action="<?php echo $base_url; ?>/search/result" method="get" id="searchForm">
		<input type='text' name='r' id="r" />
		<paper-icon-button icon="search" title="search" class="search" id="submit"></paper-icon-button>
	</form>
</paper-shadow>

<!--ナビ-->
<paper-shadow class="nav" z="1" id="status" data-value = "0">
    <paper-tabs class="bottom_fit" selected="0">
        <paper-tab onclick="setMaterial();">Part</paper-tab>
        <paper-tab onclick="setRecord();">Mix</paper-tab>
        <paper-tab onclick="setUser();">User</paper-tab>
    </paper-tabs>
</paper-shadow>

<div id="contents">
</div>