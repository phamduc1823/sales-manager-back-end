<?php

return [
    'name' => 'Đơn bán hàng',
    'form' => '
       <div>
<p><strong>{Ten_Cua_hang}</strong></p>

<p>{Ten_Chi_Nhanh} - {Dia_Chi_Chi_Nhanh}</p>

<p>{Dien_Thoai_Chi_Nhanh}</p>

<hr />
<p>&nbsp;</p>

<h2 style="text-align:center"><strong>HÓA ĐƠN BÁN HÀNG</strong></h2>

<p>&nbsp;</p>

<div>
<div>
<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td><strong>Ngày: </strong>{Ngay_Tao}</td>
			<td><strong>HĐ: {Ma_Don_Hang}</strong></td>
		</tr>
		<tr>
			<td><strong>Nh&acirc;n vi&ecirc;n: </strong>&nbsp;{Nguoi_Phu_Trach}</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
</div>
</div>

<div>
<table align="center" border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="text-align:justify; width:40%">
			<h3><strong>Sản phẩm</strong></h3>
			</td>
			<td style="text-align:justify">
			<h3><strong>Đơn gía</strong></h3>
			</td>
			<td style="text-align:justify">
			<h3><strong>Số lượng</strong></h3>
			</td>
			<td style="text-align:justify">
			<h3><strong>Thành tiền</strong></h3>
			</td>
		</tr>
		{Listproducts}
		<tr>
			<td>Tổng tiền hàng</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Tong_Tien_Hang}</td>
		</tr>
		<tr>
			<td>Chiết khấu SP</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Tong_Chiet_Khau_San_Pham}</td>
		</tr>
		<tr>
			<td>Chiết khấu đơn</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Chiet_Khau_Don_Hang}</td>
		</tr>
		<tr>
			<td>Tổng tiền thuế</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Tong_Thue}</td>
		</tr>
		<tr>
			<td>Phí khác</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Phi_Khac}</td>
		</tr>
		<tr>
			<td>
			<h3><strong>TỔNG TIỀN</strong></h3>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Tong_Can_Thanh_Toan}</td>
		</tr>
		<tr>
			<td>Tiền khách đưa</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Khach_Thanh_Toan}</td>
		</tr>
		<tr>
			<td>Ghi nợ</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Tien_No}</td>
		</tr>
		<tr>
			<td>Tiền trả lại</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Tien_Tra}</td>
		</tr>
		<tr>
			<td>
			<h4><strong>Khách hàng: </strong></h4>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Ten_Khach_Hang}</td>
		</tr>
		<tr>
			<td>
			<h4><strong>SĐT:&nbsp;&nbsp;</strong></h4>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{Dien_Thoai_Khach}</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<hr />
<p>&nbsp;</p>
</div>

<h3 style="text-align:center"><strong>CẢM ƠN QUÝ KHÁCH VÀ HẸN GẶP LẠI</strong></h3>

<h3 style="text-align:center"><strong>Powered by NextGo</strong></h3>
</div>

        <script type="text/javascript">
            function checkAndHideId(targetId, condition, containerIds) {
              var target = document.getElementById(targetId);
              var targetValue = target.innerHTML.trim();
              if (targetValue == "&nbsp;" || targetValue == condition) {
                for (const containerId of containerIds) {
                  var container = document.getElementById(containerId);
                  container.style.display = "none";
                }
              }
              return;
            }
            function checkAndHideClass(containerClass, condition, targetClass) {
              var containers = document.getElementsByClassName(containerClass);
              for (const container of containers) {
                var targets = container.getElementsByClassName(targetClass);
                if (targets) {
                  if (
                    targets[0] &&
                    (targets[0].innerHTML.trim() == "&nbsp;" ||
                      targets[0].innerHTML.trim() == condition)
                  ) {
                    container.style.display = "none";
                  }
                }
              }
              return;
            }
            checkAndHideId("mc_address", "", ["mc_address"]);
            checkAndHideId("mc_phone", "", ["mc_phone"]);
            checkAndHideId("bill_debt", "0", ["bill_debt_container"]);
            checkAndHideId("customer_name", "Khách vãng lai", [
              "customer_name_container",
              "bill-bottom-dash-line",
            ]);
            checkAndHideId("customer_name", "Khách vãng lai", [
              "customer_phone_container",
            ]);
            checkAndHideClass(
              "bill_item_promotion_container",
              "0",
              "bill_item_promotion"
            );
            checkAndHideClass("bill_item_tax_container", "0", "bill_item_tax");
        </script>
    ',
    'default' => 1,
];
