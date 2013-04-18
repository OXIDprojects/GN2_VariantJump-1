<?php
class GN2_VariantJump extends GN2_VariantJump_parent
{
    public function render()
    {

        // Load Settings
        $data = parse_ini_file(dirname(__FILE__) . '/gn2_variantjump.ini', true);
        $mode = $data['settings']['mode'];
        $observers = explode(',', $data['settings']['observers']);

        // Load Article and Variantlist
        $parent = $this->getProduct()->getParentArticle();
        $variants = $this->getVariantList();

        $redirect = false;

        if (!is_object($parent)) {
            foreach ($variants as $variant) {
                if (is_object($variant)) {

                    // Switch Standard Modes
                    switch ($mode) {
                        case "1";
                            // You're the first one. JUMP!
                            $redirect = true;
                            break;

                        case "2";
                            // 0 = green, 1 = yellow, -1 = red
                            $stock = $variant->getStockStatus();

                            // if is not out of stock
                            if($stock >= 0){
                                $redirect = true;
                            }
                            break;

                        default:
                            break;
                    }

                    // Special-Functions - Observe lika Boss
                    foreach ($observers as $observer) {
                        $fn = dirname(__FILE__).'/'.$observer.'.php';
                        if ($observer != "" && file_exists($fn)) {
                            include_once $fn;
                            if (class_exists($observer)) {
                                $object = new $observer;
                                $redirect = $object->check($variant, $parent, $mode, $redirect);
                            }
                        }
                    }

                    // redirect to this variant if redirecting is enabled
                    if($redirect){
                        $link = $variant->getLink();
                        header('Location:'.$link);
                        die();
                    }
                }
            }
        }
        return parent::render();
    }
}
