

SELECT pav.*, a.code, a.type FROM `product_attribute_values` pav join attributes a on pav.attribute_id = a.id
