<table id="<?php echo XMN5_CSS_PREFIX; ?>_messages_table">
    <thead>
        <tr>
            <th></th>
            <th>Name <span id="<?php echo XMN5_CSS_PREFIX; ?>_unread_messages_counter" class="badge badge-pill badge-info">0</span></th>
            <th>Phone</th>
            <th>Email</th>
            <th>Reciption Date</th>
        </tr>
    </thead>
    <tbody>
        <?php      
            $messages = $this->get_all_messages();   
            foreach($messages as $key=>$value) { ?>

                <tr data-id="<?php echo $value["id"]?>" data-is_read="<?php echo $value["is_read"] ?>" data-is_replied="<?php echo $value["is_replied"] ?>">
                    <td><input type="checkbox" /></td>
                    <td><?php echo $value["name"]?></td>
                    <td><?php echo $value["phone"] ?></td>
                    <td><?php echo $value["email"] ?></td>
                    <td><?php echo $value["reciption_date"] ?></td>
                </tr>                                    
        <?php } ?>
    </tbody>
</table>
