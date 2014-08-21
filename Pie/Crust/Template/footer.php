			<footer class="row bg1">
				<div class="footer">
					<div class="two">
						<ul>
							<li>&copy; <?php echo date('Y'); ?> StatusPeople.com. Built by <a href="http://twitter.com/statuspeople" target="_blank" class="whitelink">@StatusPeople</a></li>
						</ul>
					</div> 
					<div class="two right">
						<ul>
							<li><a href="/Fakers/FindOutMore/">Find Out More</a></li>
							<li><a href="/Fakers/Terms/">Terms</a></li>
							<?php if ($logout != 2) { ?>
							<li><a href="/Fakers/Reset">Reset Twitter</a></li>
							<?php } ?>
						</ul>
						<ul>
							<?php if ($logout != 2) { ?>
								<li><a href="/Fakers/Scores">Dashboard</a></li>
							<?php } ?>
							<?php if (!$logout) { ?>
								<li><a href="/Fakers/Followers">Analytics</a></li>
							<?php } ?>
							<li><a href="/Fakers/Help">Help</a></li>
							<?php if ($logout != 2) { ?>
							<li><a href="/Payments/Subscriptions">Subscriptions</a></li>
							<li><a href="/Fakers/Settings">Settings</a></li>
							<?php } ?>
						</ul>
						<ul>
							<li><a href="http://twitter.com/statuspeople" target="_blank" class="whitelink">Twitter</a></li>
							<li><a href="http://www.facebook.com/pages/StatusPeople/206864836039961" target="_blank" class="whitelink">Facebook</a></li>
							<li><a href="http://statuspeople.com" target="_blank">StatusPeople.com</a></li>
						</ul>
					</div>
				</div>
			</footer>
			<script src="/Pie/Crust/Template/minify/js/spam.class.min.js"></script>
			<script src="/Pie/Crust/Template/minify/js/closemessages.min.js"></script>
		</div>
    </body>
</html>