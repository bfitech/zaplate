<?php


namespace BFITech\ZapTemplate;


/**
 * Template class.
 */
class Template {

	/**
	 * Constructor.
	 */
	public function __construct() {
		# nothing to be done
	}

	/**
	 * Template loader.
	 *
	 * @param string $template Path to template file, a regular PHP file
	 *     containing variables registered in $args.
	 * @param array $args Associative arrays with keys corresponding
	 *     variables in the $template. Each element can nest another
	 *     array. It depends on the template to render it.
	 * @param array $filter_args Numeric arrays containing function that
	 *     takes $args as argument and return one new array. Typical
	 *     usage is applying htmlspecialchars() to each element to
	 *     prevent HTML breakage.
	 * @param bool $buffered When true, a string of rendered template is
	 *     returned instead of directly echoed. Useful if you want to do
	 *     some post-processing such as minifying or caching to
	 *     filesystem.
	 *
	 * #### Example:
	 * @code
	 *
	 * # template.php
	 *
	 * <p><?php echo $group ?></p>
	 * <ul>
	 * <?php foreach ($members as $member): ?>
	 *     <li><?php echo $member ?></li>
	 * <?php endforeach; ?>
	 * </ul>
	 *
	 * # renderer.php
	 *
	 * class Filter {
	 *     public function whoami($name) {
	 *         if (is_string($name))
	 *             return $name;
	 *         return array_map(function($iname){
	 *             if (stripos($iname, 'jekyll') !== false)
	 *                 return 'Mr Hyde';
	 *             return $iname;
	 *         }, $name);
	 *     }
	 * }
	 *
	 * Template::load('template.php', [
	 *     'group' => "Extraordinary Gents",
	 *     'members' => [
	 *         'Allan Quatermain',
	 *         'Henry Jekyll',
	 *     ],
	 * ], [
	 *     [(new Filter), 'whoami']
	 * ]);
	 *
	 * @endcode
	 */
	public static function load(
		$template, $args=[], $filter_args=[], $buffered=false
	) {

		if ($buffered)
			ob_start();

		if ($filter_args) {
			foreach ($filter_args as $filter)
				$args = array_map($filter, $args);
		}

		extract($args, EXTR_SKIP);

		require($template);

		if ($buffered)
			return ob_get_clean();
	}

	# That's all, folks.
}
