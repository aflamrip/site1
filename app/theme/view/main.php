<?php require PATH . '/theme/view/common/header.php';?>
<div class="app-content pt-md-3"> 
    <?php echo ads($Ads,3,'mb-3');?>
    <?php  
    
    foreach ($HomeModules as $HomeModule) {
        $ModuleData       = json_decode($HomeModule['data'], true);
        require PATH . '/theme/view/module/'.$HomeModule['module_file'].'.php';
    } 
?>
</div>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "url": "<?php echo APP;?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "<?php echo APP.'/search/';?>{search_term_string}"
        },
        "query-input": "required name=search_term_string"
      }
    }
</script>
<?php require PATH . '/theme/view/common/footer.php';?>