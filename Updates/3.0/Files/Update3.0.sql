ALTER TABLE
  plugins RENAME TO extensions;

ALTER TABLE
  email_logs RENAME TO notification_logs;

ALTER TABLE
  email_sms_templates RENAME TO notification_templates;

CREATE TABLE `forms` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `form_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO
  `forms` (`id`,`act`, `form_data`, `created_at`, `updated_at`)
VALUES
  (
    1,
    'kyc',
    '{\"full_name\":{\"name\":\"Full Name\",\"label\":\"full_name\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"nid_number\":{\"name\":\"NID Number\",\"label\":\"nid_number\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"gender\":{\"name\":\"Gender\",\"label\":\"gender\",\"is_required\":\"required\",\"extensions\":null,\"options\":[\"Male\",\"Female\",\"Others\"],\"type\":\"select\"},\"you_hobby\":{\"name\":\"You Hobby\",\"label\":\"you_hobby\",\"is_required\":\"required\",\"extensions\":null,\"options\":[\"Programming\",\"Gardening\",\"Traveling\",\"Others\"],\"type\":\"checkbox\"},\"nid_photo\":{\"name\":\"NID Photo\",\"label\":\"nid_photo\",\"is_required\":\"required\",\"extensions\":\"jpg,png\",\"options\":[],\"type\":\"file\"}}',
    '2022-03-17 02:56:14',
    '2022-04-11 03:23:40'
  );

  
ALTER TABLE
  `frontends`
ADD
  `template_name` VARCHAR(40) NULL DEFAULT NULL
AFTER
  `data_values`;

UPDATE
  `frontends`
SET
  `template_name` = 'global'
WHERE
  `data_keys` = 'seo.data';

INSERT INTO
  `frontends` (`data_keys`, `data_values`, `template_name`)
VALUES
  (
    'cookie.data',
    '{\"short_desc\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.\",\"description\":\"<div class=\\\"mb-5\\\">\\r\\n    <h3 class=\\\"mb-3\\\">\\r\\n        What information do we collect?<\\/h3>\\r\\n    <p class=\\\"font-18\\\">We gather data from you\\r\\n        when you register on our site, submit a request, buy any services, react to an overview, or round out a\\r\\n        structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be\\r\\n        approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site\\r\\n        anonymously.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h3 class=\\\"mb-3\\\">\\r\\n        How do we protect your information?<\\/h3>\\r\\n    <p class=\\\"font-18\\\">All provided\\r\\n        delicate\\/credit data is sent through Stripe.<br>After an exchange, your private data (credit cards, social\\r\\n        security numbers, financials, and so on) won\'t be put away on our workers.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h3 class=\\\"mb-3\\\">\\r\\n        Do we disclose any information to outside parties?<\\/h3>\\r\\n    <p class=\\\"font-18\\\">We don\'t sell, exchange,\\r\\n        or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders\\r\\n        who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep\\r\\n        this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law,\\r\\n        implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h3 class=\\\"mb-3\\\">\\r\\n        Children\'s Online Privacy Protection Act Compliance<\\/h3>\\r\\n    <p class=\\\"font-18\\\">We are consistent with\\r\\n        the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody\\r\\n        under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in\\r\\n        any event 13 years of age or more established.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h3 class=\\\"mb-3\\\">\\r\\n        Changes to our Privacy Policy<\\/h3>\\r\\n    <p class=\\\"font-18\\\">If we decide to change\\r\\n        our privacy policy, we will post those changes on this page.<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h3 class=\\\"mb-3\\\">\\r\\n        How long we retain your information?<\\/h3>\\r\\n    <p class=\\\"font-18\\\">At the point when you\\r\\n        register for our site, we cycle and keep your information we have about you however long you don\'t erase the\\r\\n        record or withdraw yourself (subject to laws and guidelines).<\\/p>\\r\\n<\\/div>\\r\\n<div class=\\\"mb-5\\\">\\r\\n    <h3 class=\\\"mb-3\\\">\\r\\n        What we don\\u2019t do with your data<\\/h3>\\r\\n    <p class=\\\"font-18\\\">We don\'t and will never\\r\\n        share, unveil, sell, or in any case give your information to different organizations for the promoting of their\\r\\n        items or administrations.<\\/p>\\r\\n<\\/div>\",\"status\":1}',
    'global'
  ),
  (
    'maintenance.data',
    '{\"description\":\"<div class=\\\"text-center\\\"><h3 class=\\\"mb-3\\\">What information do we collect?<\\/h3>\\r\\n<p class=\\\"font-18\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div>\"}',
    'global'
  );


UPDATE
  `frontends`
SET
  `template_name` = 'bit_gold'
WHERE
  `template_name` IS NULL;


ALTER TABLE
  `admins`
ADD
  `remember_token` VARCHAR(255) NULL DEFAULT NULL
AFTER
  `password`;



ALTER TABLE
  `admins` CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `username` `username` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `admin_notifications` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `read_status` `read_status` TINYINT(1) NOT NULL DEFAULT '0',
  CHANGE `click_url` `click_url` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `admin_password_resets` CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `token` `token` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

DROP TABLE commission_logs;


ALTER TABLE
  `deposits`
ADD
  `from_api` TINYINT(1) NULL DEFAULT 0
AFTER
  `status`;

ALTER TABLE
  `deposits` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  CHANGE `plan_id` `plan_id` INT(10) UNSIGNED NULL DEFAULT '0',
  CHANGE `method_code` `method_code` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  CHANGE `amount` `amount` DECIMAL(28, 8) NOT NULL,
  CHANGE `method_currency` `method_currency` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `charge` `charge` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `rate` `rate` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `final_amo` `final_amo` DECIMAL(28, 8) NULL DEFAULT '0.00000000',
  CHANGE `btc_amo` `btc_amo` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `btc_wallet` `btc_wallet` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `trx` `trx` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `try` `try` INT(10) NOT NULL DEFAULT '0',
  CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel',
  CHANGE `admin_feedback` `admin_feedback` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `extensions` CHANGE `act` `act` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `gateways` CHANGE `name` `name` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
AFTER
  `code`;


ALTER TABLE
  `gateways`
ADD
  `form_id` INTEGER(10) UNSIGNED NULL DEFAULT 0
AFTER
  `id`;


ALTER TABLE
  `gateways` DROP `image`,
  DROP `input_form`;

ALTER TABLE
  `gateways` CHANGE `code` `code` INT(10) NULL DEFAULT NULL,
  CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `alias` `alias` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  CHANGE `parameters` `gateway_parameters` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `gateway_currencies` CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `currency` `currency` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `symbol` `symbol` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `method_code` `method_code` INT(10) NULL DEFAULT NULL,
  CHANGE `gateway_alias` `gateway_alias` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `min_amount` `min_amount` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `max_amount` `max_amount` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `fixed_charge` `fixed_charge` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `rate` `rate` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `general_settings` CHANGE `sms_api` `sms_body` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `sys_version` `system_info` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `general_settings` CHANGE `sitename` `site_name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `general_settings`
ADD
  `sms_from` VARCHAR(255) NULL DEFAULT NULL
AFTER
  `sms_body`;

ALTER TABLE
  `general_settings` DROP `secondary_color`;

ALTER TABLE
  `general_settings` CHANGE `sms_config` `sms_config` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `mail_config`;


ALTER TABLE
  `general_settings`
ADD
  `global_shortcodes` TEXT NULL DEFAULT NULL
AFTER
  `sms_config`,
ADD
  `kv` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `global_shortcodes`;

ALTER TABLE
  `general_settings`
ADD
  `force_ssl` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `sn`,
ADD
  `maintenance_mode` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `force_ssl`,
ADD
  `secure_password` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `maintenance_mode`,
ADD
  `agree` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `secure_password`;

ALTER TABLE
  `general_settings` CHANGE `registration` `registration` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: Off , 1: On'
AFTER
  `agree`,
  CHANGE `active_template` `active_template` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `registration`,
  CHANGE `system_info` `system_info` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `active_template`,
  CHANGE `deposit_commission` `deposit_commission` TINYINT(1) NOT NULL DEFAULT '1'
AFTER
  `system_info`,
  CHANGE `invest_commission` `invest_commission` TINYINT(1) NOT NULL DEFAULT '1'
AFTER
  `deposit_commission`,
  CHANGE `invest_return_commission` `invest_return_commission` TINYINT(1) NOT NULL DEFAULT '1'
AFTER
  `invest_commission`,
  CHANGE `signup_bonus_amount` `signup_bonus_amount` DECIMAL(11, 2) NULL DEFAULT '0.00'
AFTER
  `invest_return_commission`,
  CHANGE `signup_bonus_control` `signup_bonus_control` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `signup_bonus_amount`,
  CHANGE `off_day` `off_day` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `signup_bonus_control`,
  CHANGE `last_cron` `last_cron` DATETIME NULL DEFAULT NULL
AFTER
  `off_day`;


ALTER TABLE
  `general_settings` DROP `social_login`,
  DROP `social_credential`;

ALTER TABLE
  `general_settings`
ADD
  `promotional_tool` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `signup_bonus_control`;

ALTER TABLE
  `general_settings` CHANGE `f_charge` `f_charge` DECIMAL(18, 8) NOT NULL DEFAULT '0.00000000' COMMENT 'Balance Transfer Fixed Charge'
AFTER
  `b_transfer`;


ALTER TABLE
  `general_settings`
ADD
  `holiday_withdraw` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `p_charge`,
ADD
  `language_switch` TINYINT(1) NOT NULL DEFAULT '1'
AFTER
  `holiday_withdraw`;

ALTER TABLE
  `general_settings` CHANGE `cur_text` `cur_text` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'currency text',
  CHANGE `cur_sym` `cur_sym` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'currency symbol',
  CHANGE `email_from` `email_from` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `base_color` `base_color` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `active_template` `active_template` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `signup_bonus_amount` `signup_bonus_amount` DECIMAL(28, 8) NULL DEFAULT '0.00',
  CHANGE `b_transfer` `b_transfer` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Balance Transfer',
  CHANGE `f_charge` `f_charge` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000' COMMENT 'Balance Transfer Fixed Charge',
  CHANGE `p_charge` `p_charge` DECIMAL(5, 2) NOT NULL DEFAULT '0.00000000' COMMENT 'Balance Transfer Percent Charge';

ALTER TABLE
  `holidays`
ADD
  `title` VARCHAR(40) NULL DEFAULT null
AFTER
  `id`;


ALTER TABLE
  `invests`
ADD
  `should_pay` DECIMAL(28, 8) NOT NULL DEFAULT '0'
AFTER
  `interest`,
ADD
  `paid` DECIMAL(28, 8) NOT NULL DEFAULT '0'
AFTER
  `should_pay`;

UPDATE
  invests
SET
  should_pay = interest * (period - return_rec_time)
WHERE
  period != '-1';

UPDATE
  invests
SET
  paid = (interest * return_rec_time);


UPDATE
  invests
SET
  should_pay = '-1'
WHERE
  period = '-1';

ALTER TABLE
  `invests`
ADD
  `wallet_type` VARCHAR(40) NULL DEFAULT NULL
AFTER
  `trx`;

ALTER TABLE
  `invests` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL,
  CHANGE `plan_id` `plan_id` INT(10) UNSIGNED NOT NULL,
  CHANGE `amount` `amount` DECIMAL(28, 8) NOT NULL DEFAULT '0.00',
  CHANGE `interest` `interest` DECIMAL(28, 8) NOT NULL DEFAULT '0.00',
  CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '1',
  CHANGE `capital_status` `capital_status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = YES & 0 = NO',
  CHANGE `trx` `trx` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `languages` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `code` `code` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `icon` `icon` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `notification_logs` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  CHANGE `mail_sender` `sender` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `from` `sent_from` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `to` `sent_to` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `notification_logs`
ADD
  `notification_type` VARCHAR(40) NULL DEFAULT NULL
AFTER
  `message`;

ALTER TABLE
  `notification_templates` CHANGE `id` `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  CHANGE `act` `act` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `subj` `subj` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `shortcodes` `shortcodes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `email_status` `email_status` TINYINT(1) NOT NULL DEFAULT '1',
  CHANGE `sms_status` `sms_status` TINYINT(1) NOT NULL DEFAULT '1';

ALTER TABLE
  `pages` CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `slug` `slug` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `tempname` `tempname` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'template name',
  CHANGE `is_default` `is_default` TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE
  `password_resets` CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  CHANGE `token` `token` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

DROP TABLE personal_access_tokens;

ALTER TABLE
  `plans` CHANGE `id` `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `minimum` `minimum` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `maximum` `maximum` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `fixed_amount` `fixed_amount` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `interest` `interest` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `interest_status` `interest_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = \'%\' / 0 =\'currency\'',
  CHANGE `times` `time` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '1',
  CHANGE `featured` `featured` TINYINT(1) NOT NULL DEFAULT '0',
  CHANGE `capital_back_status` `capital_back` TINYINT(1) NOT NULL DEFAULT '0',
  CHANGE `lifetime_status` `lifetime` TINYINT(1) NOT NULL DEFAULT '0',
  CHANGE `repeat_time` `repeat_time` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `plans`
ADD
  `time_name` VARCHAR(40) NULL DEFAULT NULL
AFTER
  `time`;

ALTER TABLE
  `promotion_tools` CHANGE `name` `name` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  CHANGE `banner` `banner` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE
  `referrals` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  CHANGE `commission_type` `commission_type` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `level` `level` INT(11) NOT NULL DEFAULT '0',
  CHANGE `percent` `percent` DECIMAL(5, 2) NOT NULL DEFAULT '0',
  CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '1';

ALTER TABLE
  `subscribers` CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `support_attachments` DROP `image`;

ALTER TABLE
  `support_attachments` CHANGE `support_message_id` `support_message_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE
  `support_attachments`
ADD
  `attachment` VARCHAR(255) NULL DEFAULT NULL
AFTER
  `support_message_id`;

ALTER TABLE
  `support_messages` CHANGE `supportticket_id` `support_ticket_id` INT(10) UNSIGNED NULL DEFAULT '0',
  CHANGE `admin_id` `admin_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE
  `support_tickets` CHANGE `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT '0',
  CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `ticket` `ticket` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `subject` `subject` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed';

ALTER TABLE
  `time_settings` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `time` `time` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `transactions` CHANGE `details` `details` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `trx`;

ALTER TABLE
  `transactions`
ADD
  `remark` VARCHAR(40) NULL DEFAULT NULL
AFTER
  `details`;

ALTER TABLE
  `transactions` CHANGE `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT '0',
  CHANGE `amount` `amount` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `charge` `charge` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `post_balance` `post_balance` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `trx_type` `trx_type` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `trx` `trx` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `wallet_type` `wallet_type` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `users`
ADD
  `country_code` VARCHAR(40) NULL DEFAULT NULL
AFTER
  `email`;

ALTER TABLE
  `users`
ADD
  `kyc_data` TEXT NULL DEFAULT NULL
AFTER
  `status`,
ADD
  `kv` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `kyc_data`;

ALTER TABLE
  `users`
ADD
  `reg_step` TINYINT(1) NOT NULL DEFAULT '0'
AFTER
  `sv`;

UPDATE
  users
SET
  reg_step = 1;


ALTER TABLE
  `users` DROP `provider`,
  DROP `provider_id`;

ALTER TABLE
  `users`
ADD
  `ban_reason` VARCHAR(255) NULL DEFAULT NULL
AFTER
  `tsc`;

ALTER TABLE
  `users` CHANGE `deposit_wallet` `deposit_wallet` DECIMAL(18, 8) NOT NULL DEFAULT '0.00000000'
AFTER
  `ref_by`,
  CHANGE `interest_wallet` `interest_wallet` DECIMAL(18, 8) NOT NULL DEFAULT '0.00000000'
AFTER
  `deposit_wallet`;

ALTER TABLE
  `users` CHANGE `firstname` `firstname` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `lastname` `lastname` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `username` `username` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  CHANGE `email` `email` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `mobile` `mobile` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `ref_by` `ref_by` INT(10) UNSIGNED NULL DEFAULT '0',
  CHANGE `deposit_wallet` `deposit_wallet` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `interest_wallet` `interest_wallet` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `ver_code` `ver_code` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'stores verification code',
  CHANGE `remember_token` `remember_token` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `user_logins` DROP `location`;

ALTER TABLE
  `user_logins` CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  CHANGE `user_ip` `user_ip` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `browser` `browser` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `os` `os` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `longitude` `longitude` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `latitude` `latitude` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `country` `country` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `country_code` `country_code` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `city` `city` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE
  `user_logins` CHANGE `city` `city` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `user_ip`,
  CHANGE `country` `country` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `city`,
  CHANGE `country_code` `country_code` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `country`,
  CHANGE `longitude` `longitude` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `country_code`,
  CHANGE `latitude` `latitude` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
AFTER
  `longitude`;

ALTER TABLE
  `withdrawals` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  CHANGE `amount` `amount` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `currency` `currency` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `rate` `rate` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `charge` `charge` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `trx` `trx` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `final_amount` `final_amount` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `after_charge` `after_charge` DECIMAL(28, 8) NOT NULL DEFAULT '0',
  CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel, ';

ALTER TABLE
  `withdraw_methods` DROP `delay`;

ALTER TABLE
  `withdraw_methods` DROP `user_data`;

ALTER TABLE
  `withdraw_methods` DROP `image`;

ALTER TABLE
  `withdraw_methods` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  CHANGE `min_limit` `min_limit` DECIMAL(28, 8) NULL DEFAULT '0',
  CHANGE `max_limit` `max_limit` DECIMAL(28, 8) NOT NULL DEFAULT '0.00000000',
  CHANGE `fixed_charge` `fixed_charge` DECIMAL(28, 8) NULL DEFAULT '0',
  CHANGE `rate` `rate` DECIMAL(28, 8) NULL DEFAULT '0',
  CHANGE `percent_charge` `percent_charge` DECIMAL(5, 2) NULL DEFAULT '0',
  CHANGE `currency` `currency` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;



ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;



UPDATE `extensions` SET `shortcode` = '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"-----\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"------\"}}' WHERE `act` = 'google-recaptcha2';

UPDATE `extensions` SET `script` = '\r\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\r\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\r\n<div id=\"g-recaptcha-error\"></div>' WHERE `act` = 'google-recaptcha2';

UPDATE `general_settings` SET `global_shortcodes` = '{\r\n    \"site_name\":\"Name of your site\",\r\n    \"site_currency\":\"Currency of your site\",\r\n    \"currency_symbol\":\"Symbol of currency\"\r\n}';