<?php $this->setLayoutVar('title', 'Home') ?>
<style>
#contents {
    width: 95%;
    padding-top: 65px;
    padding-bottom: 55px;
    margin: 0 auto;
}
</style>

<script type="text/javascript" src="<?php echo $base_url; ?>/js/home.js"></script>
<div id="nav">
    <paper-tabs selected="0">
        <paper-tab onclick="getMaterial()">Part</paper-tab>
        <paper-tab onclick="getRecord()">Mix</paper-tab>
        <paper-tab onclick="getFavorite()">Favorite</paper-tab>
    </paper-tabs>
</div>

<ul class="accordion">
    <li>
        <div id="contents"></div>
    </li>
</ul>