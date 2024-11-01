<?php defined( 'ABSPATH' ) || die(); ?>
<div class="hrcwtm-wrap">
    <div class="hrcwtm-header">
        <h2 class="hrcwtm-header__title"><?php esc_html_e( 'Plugin WPTelMessage', 'wptelmessage' ); ?></h2>
        <p class="hrcwtm-header__description"><?php esc_html_e( 'With the WPTelMessage plugin, you can receive messages from website contact forms and order notifications from Woocommerce in Telegram.', 'wptelmessage' ); ?></p>
    </div>
    <div class="hrcwtm-main-block">
        <div class="hrcwtm-left-block">
            <div class="hrcwtm-section">
                <h3 class="hrcwtm-section__title"><?php esc_html_e( 'Instructions for setting up the WPTelMessage plugin', 'wptelmessage' ); ?></h3>
                <div class="hrcwtm-section__description">
                  <p><?php esc_html_e( 'To receive messages from the website on Telegram, you need to perform a few simple steps to configure the plugin.', 'wptelmessage' ); ?></p>
                  <ol class="hrcwtm-section__description-list">
                      <li><?php esc_html_e( 'Create a group on Telegram and find out its number, for example, using the ', 'wptelmessage' ); ?><a href="<?php echo esc_url( "https://t.me/MyChatInfoBot" ) ?>" target="_blank"><?php esc_html_e( ' MyChatInfoBot.', 'wptelmessage' ); ?></a></li>
                      <li><?php esc_html_e( 'Create a Telegram bot using ', 'wptelmessage' ); ?><a href="<?php echo esc_url( "https://t.me/BotFather" ) ?>" target="_blank">@BotFather</a> <?php esc_html_e( 'and obtain its token.', 'wptelmessage' ); ?></li>
                      <li><?php esc_html_e( 'Make your Telegram bot a member of the created group.', 'wptelmessage' ); ?></li>
                      <li><?php esc_html_e( 'Enter the number of your group in the Group ID field, and your bot\'s token in the Bot Token field.', 'wptelmessage' ); ?></li>
                      <li><?php esc_html_e( 'Activate the form from which you want to receive messages in Telegram, as well as the necessary options.', 'wptelmessage' ); ?></li>
                      <li><?php esc_html_e( 'Save the changes using the "Save Changes" button.', 'wptelmessage' ); ?></li>
                  </ol>
                  <p><?php esc_html_e( 'The plugin is ready to use!', 'wptelmessage' ); ?></p>
                  
                </div>
            </div>
            <div class="hrcwtm-section">
                <form method="post" action="options.php">
                    <?php settings_fields( \Hrcode\WpTelMessage\WpTelMessageSetting::GROUP_NAME ); ?>
                    <?php do_settings_sections( \Hrcode\WpTelMessage\WpTelMessageSetting::PAGE_SLUG ); ?>
                    <?php submit_button( __( 'Save Changes', 'wptelmessage' ) ); ?>
                </form>

            </div>
        </div><!-- end hrcwtm-left-block -->
        <div class="hrcwtm-right-block">
            <div class="hrcwtm-section">
               <a href="<?php echo esc_url( "https://prowebcode.ru/plagin-wptelmessage/") ?>" target="_blank">
                   <img class="hrcwtm-logo" src="<?php echo esc_url( plugins_url( 'assets/img/wptelmessage.webp', dirname( __FILE__ ) ) ); ?>">
               </a>
               <p><?php esc_html_e( 'You can find a detailed description of the plugin and its settings on our website.', 'wptelmessage' ); ?></p>
               <a href="<?php echo esc_url( "https://prowebcode.ru/plagin-wptelmessage/" ) ?>" target="_blank">
                <?php esc_html_e( 'Go to the website', 'wptelmessage' ); ?>
               </a>
            </div>
            <div class="hrcwtm-section">
                <h4 class="hrcwtm-section__title"><?php esc_html_e( 'Additional Instructions', 'wptelmessage' ); ?></h4>
                <p><?php esc_html_e( 'Detailed instructions on how to create a bot and a group in Telegram.', 'wptelmessage' ); ?></p>
                <ul>
                    <li>
                        <a href="<?php echo esc_url( "https://prowebcode.ru/kak-sozdat-telegram-bota/" ) ?>" target="_blank">
                        <?php esc_html_e( 'How to create a Telegram bot', 'wptelmessage' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( "https://prowebcode.ru/kak-sozdat-gruppu-v-telegram/" ) ?>" target="_blank">
                        <?php esc_html_e( 'How to create a group in Telegram and get its ID', 'wptelmessage' ); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="hrcwtm-section">
                <h4 class="hrcwtm-section__title"><?php esc_html_e( 'Brief description of plugin functions', 'wptelmessage' ); ?></h4>
                <h3>Contact Form 7</h3>
                <p><?php esc_html_e( 'The plugin supports tags in the format: [your-name], [your-email], [your-message]', 'wptelmessage'); ?></p>
                <p><?php esc_html_e( 'These tags support text translation and will be displayed as: Name, Email, Message.', 'wptelmessage' );?></p>
                <p><?php esc_html_e( 'If you use custom tags, they will be shown in the message as is. More detailed information on using tags can be found ', 'wptelmessage' );?><a href="<?php echo esc_url( "https://prowebcode.ru/plagin-wptelmessage/" ) ?>" target="_blank"><?php esc_html_e( 'here.', 'wptelmessage' ); ?></a></p>
                <h3>WooCommerce</h3>
                <p><?php esc_html_e( 'The plugin sends messages for the following events:', 'wptelmessage' ); ?></p>
                <p><em><?php esc_html_e( '"Addition of a product to the cart", "Removal of a product from the cart", "Change in the status of a product", "Order completion", "Low inventory of a product".', 'wptelmessage' ); ?></em></p>
                <p><?php esc_html_e( 'Each of these events sends specific data to Telegram.', 'wptelmessage' ); ?></p>
                <p><strong><?php esc_html_e( 'Adding and removing a product from the cart:', 'wptelmessage' ); ?></strong></p>
                <p><ul>
                    <li><em><?php esc_html_e( 'Information about the site from which the message came', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Username', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Product name', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Product price', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Product quantity', 'wptelmessage' ); ?></em></li>
                    </ul></p>
                <p><strong><?php esc_html_e( 'Changing order status and its completion:', 'wptelmessage' ); ?></strong></p>
                <p><ul>
                    <li><em><?php esc_html_e( 'Information about the site from which the message came', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Order number', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Order cost', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Order status', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Payment method', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Username', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Email address of the user', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Phone number', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Order time', 'wptelmessage' ); ?></em></li>
                <p><strong><?php esc_html_e( 'Low inventory information:', 'wptelmessage' ); ?></strong></p>
                </ul></p>
                <p><ul>
                    <li><em><?php esc_html_e( 'Information about the site from which the message came', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Product name', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Product availability', 'wptelmessage' ); ?></em></li>
                    <li><em><?php esc_html_e( 'Link to the product page', 'wptelmessage' ); ?></em></li>
                </ul></p>
          </div>
        </div><!-- end hrcwtm-right-block -->
    </div><!-- end hrcwtm-main-block -->
</div> <!-- end wrap -->