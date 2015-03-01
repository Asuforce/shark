<?php $this->setLayoutVar('title', 'Home') ?>

<style>
#contents {
    left: 0;
    width: 95%; height: 60%;
    padding-top: 65px;
    padding-bottom: 60px;
    margin: 0 auto;
}
.timeline { margin-bottom: 0px; margin-top: 4px; }
</style>
<script type="text/javascript" src="<?php echo $base_url; ?>/js/home.js"></script>

<div id="nav">
    <paper-tabs class="bottom_fit" selected="0">
        <paper-tab onclick="getMaterial()">Part</paper-tab>
        <paper-tab onclick="getRecord()">Mix</paper-tab>
        <paper-tab onclick="getFavorite()">Favorite</paper-tab>
    </paper-tabs>
</div>

<core-drag-drop></core-drag-drop>

<ul class="accordion">
    <li>
        <div id="contents"></div>
    </li>
</ul>