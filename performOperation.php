function isMixedFraction($input)
{
	if (preg_match('/^\d+ \d+\/\d+$/', $input)) {
		return true;
	}
	// Kiểm tra phép toán với phân số (ví dụ: "+1/2", "-1/2")
	if (preg_match('/^[+-]?\d+\/\d+$/', $input)) {
		return true;
	}
	return false;
}

function isDecimalNumber($input)
{
	return preg_match('/^\d+(\.\d+)?$/', $input);
}

function isInteger($input)
{
	return preg_match('/^\d+$/', $input);
}
function convertToDecimal($string_value)
{
	if (strpos($string_value, ' ') !== false) {
		// Tách phần số và phần phân số
		list($whole_part, $fraction_part) = explode(' ', $string_value);

		// Tách phần tử số và phần số
		list($numerator, $denominator) = explode('/', $fraction_part);

		// Chuyển đổi thành số thập phân
		$decimal_value = $whole_part + ($numerator / $denominator);
	} else {
		// Nếu không có khoảng trắng, giá trị là số nguyên
		$decimal_value = $string_value;
	}
	// Trả về giá trị số thập phân
	return $decimal_value;
}

function convertToFraction($decimal_value)
{
	// Kiểm tra và chuyển đổi chuỗi thành số
	if (!is_numeric($decimal_value)) {
		throw new InvalidArgumentException("Input must be a numeric value.");
	}
	$decimal_value = (float) $decimal_value;

	if (is_int($decimal_value)) {
		return "$decimal_value";
	}
	// Chuyển đổi số thập phân thành phân số
	$whole_part = floor($decimal_value); // Phần nguyên
	$fractional_part = $decimal_value - $whole_part; // Phần thập phân
	if ($fractional_part == 0) {
		return "$whole_part";
	}
	// Tìm phân số tối giản của phần thập phân
	$precision = 1000000; // Độ chính xác cao để tính toán phân số tối giản
	$gcd = gcd(round($fractional_part * $precision), $precision);

	// Tối giản phân số
	$numerator = round($fractional_part * $precision / $gcd);
	$denominator = $precision / $gcd;

	// Nếu phần nguyên là 0 thì chỉ trả về phân số
	if ($whole_part == 0) {
		return "$numerator/$denominator";
	}

	// Nếu có phần nguyên thì kết hợp với phân số
	return "$whole_part " . "$numerator/$denominator";
}

// Hàm tính ước chung lớn nhất (Greatest Common Divisor)
function gcd($a, $b)
{
	while ($b != 0) {
		$remainder = $a % $b;
		$a = $b;
		$b = $remainder;
	}
	return abs($a);
}
function performOperation($string_value, $operation)
{
	// Chuyển đổi chuỗi ban đầu thành số thập phân
	$decimal_value = convertToDecimal($string_value);

	// Kiểm tra nếu operation là '+', cộng với 0
	if ($operation == '+' || $operation == '-') {
		$result = $decimal_value + 0;
	} else {
		// Nếu không, xử lý các phép toán khác
		// Tách dấu phép toán và phân số (nếu có)
		preg_match('/^([+-]?)(\d+)?(?:\/(\d+))?$/', $operation, $matches);

		$sign = $matches[1]; // Dấu phép toán (+ hoặc -)
		$numerator = isset($matches[2]) ? intval($matches[2]) : 0; // Phần tử số của phân số
		$denominator = isset($matches[3]) ? intval($matches[3]) : 1; // Phần số của phân số

		$fraction_decimal = $numerator / $denominator; // Chuyển đổi phân số thành số thập phân

		// Áp dụng phép toán
		if ($sign == '+') {
			$result = $decimal_value + $fraction_decimal;
		} elseif ($sign == '-') {
			$result = $decimal_value - $fraction_decimal;
		} else {
			// Nếu không phù hợp với bất kỳ trường hợp nào, giữ nguyên giá trị ban đầu
			$result = $decimal_value;
		}
	}

	// Chuyển đổi kết quả trở lại dạng phân số
	$result_fraction = convertToFraction($result);

	// Trả về kết quả dưới dạng phân số
	return $result_fraction;
}


// Ví dụ

$input = $rs['Base_Value']->value;
	$operation = $rs["ActValue"]->value;
	
	// echo $input;
	// echo "</br>" .$operation;
	if (isMixedFraction($input) || isMixedFraction($operation)) {

		// Duyệt qua các dòng kết quả từ câu truy vấn
		while (!$rs->EOF) {
			// $sum = $rs['Base_Value']->value + $rs["ActValue"]->value;
			try {
				$input = (string) $rs['Base_Value']->value;
				if ($input === null || $input === '') {
					$input = 0;
				}
				$operation = (string) $rs["ActValue"]->value;
				if ($operation === null || $operation === '') {
					$operation = 0;
				}

				$result = performOperation($input, $operation);
				// echo $result;
				if ($result === null || $result == 0 || $result === ''){
					$sum = '';
					// echo $sum;
				}
				else{
					$sum = $result;
				}

			} catch (Exception $e) {
				// echo "Error: " . $e->getMessage();
			}
// Code xử lí tại đây
}else {
// Code here ....
}

