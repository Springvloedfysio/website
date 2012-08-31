SVR_PIDFILE					= ./build/server.pid
ASSETS_ROOT					= ./public/assets
SRC_ROOT					= ./src

STYLE 						= ${ASSETS_ROOT}/css/all.css
STYLE_MIN 					= ${ASSETS_ROOT}/css/all.min.css
STYLE_LESS 					= ${SRC_ROOT}/less/all.less

JS_BOOT 					= ${ASSETS_ROOT}/js/boot.js
JS_BOOT_MIN					= ${ASSETS_ROOT}/js/boot.min.js
JS_PLUGINS 					= ${ASSETS_ROOT}/js/plugins.js
JS_PLUGINS_MIN				= ${ASSETS_ROOT}/js/plugins.min.js
JS_BS_SRC					= ${SRC_ROOT}/js/libs/bootstrap
JS_BOOTSTRAP 				= ${ASSETS_ROOT}/js/libs/bootstrap.js
JS_BOOTSTRAP_MIN			= ${ASSETS_ROOT}/js/libs/bootstrap.min.js

BUILD_ENV      ?= development
YEAR			= $(shell date +%Y)
DATE 			= $(shell date +%I:%M%p)
CHECK 			= \033[32m✔\033[39m
HR 				= \#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#


#
# BUILD DOCS
#

build: build_start jslint build_css build_js build_end

#
# RUN JSHINT & QUNIT TESTS IN PHANTOMJS
#

jslint:
	@jshint src/js/plugins/*.js --config ./build/.jshintrc
	@echo "Running JSHint on javascript plugins...       ${CHECK} Done"

test: jslint
#	node js/tests/server.js &
#	phantomjs js/tests/phantom.js "http://localhost:3000/js/tests"
#	kill -9 `cat js/tests/pid.txt`
#	rm js/tests/pid.txt

#
# VARIOUS BUILD STEPS
# recess & uglifyjs are required
#

build_start:
	@echo "\n${HR}"
	@echo "Building ..."
	@echo "${HR}\n"

build_end:
	@echo "\n${HR}"
	@echo "Successfully built at ${DATE}."
	@echo "${HR}\n"

build_css:
# remove anything from a previous build
	@rm -rf ${ASSETS_ROOT}/css/*
	@echo "Emptying CSS asset output dirs...             ${CHECK} Done"
# ensure output dirs exist
	@mkdir -p ${ASSETS_ROOT}/css
	@echo "Ensuring CSS asset output dirs exist...       ${CHECK} Done"
# build css
	@recess --compile  ${STYLE_LESS} 				> ${STYLE}
	@recess --compress ${STYLE_LESS} 				> ${STYLE_MIN}
	@echo "Compiling LESS with Recess...                 ${CHECK} Done"

build_libs_js:
# remove anything from a previous build
	@rm -rf ${ASSETS_ROOT}/js/libs/*
	@echo "Emptying JS libs asset output dirs...         ${CHECK} Done"
# ensure output dirs exist
	@mkdir -p ${ASSETS_ROOT}/js/libs
	@echo "Ensuring JS asset output dirs exist...        ${CHECK} Done"
# copy un-minified & minified versions of 3rd party libs files
	@for FILE in ${SRC_ROOT}/js/libs/*.js; do cp $$FILE ${ASSETS_ROOT}/js/libs/; uglifyjs -nc $$FILE > ${ASSETS_ROOT}/js/libs/`basename "$$FILE" .js`.min.js; done
	@echo "Compiling and minifying JS libs...            ${CHECK} Done"
# builds twitter-bootstrap-js files
	@cat ${JS_BS_SRC}/bootstrap-transition.js \
		 ${JS_BS_SRC}/bootstrap-alert.js \
		 ${JS_BS_SRC}/bootstrap-button.js \
		 ${JS_BS_SRC}/bootstrap-carousel.js \
		 ${JS_BS_SRC}/bootstrap-collapse.js \
		 ${JS_BS_SRC}/bootstrap-dropdown.js \
		 ${JS_BS_SRC}/bootstrap-modal.js \
		 ${JS_BS_SRC}/bootstrap-tooltip.js \
		 ${JS_BS_SRC}/bootstrap-popover.js \
		 ${JS_BS_SRC}/bootstrap-scrollspy.js \
		 ${JS_BS_SRC}/bootstrap-tab.js \
		 ${JS_BS_SRC}/bootstrap-typeahead.js > ${JS_BOOTSTRAP}
	@echo "/**\n* Bootstrap.js by @fat & @mdo\n* Copyright ${YEAR} Twitter, Inc.\n* http://www.apache.org/licenses/LICENSE-2.0.txt\n*/" > ${JS_BOOTSTRAP_MIN}
	@uglifyjs -nc ${JS_BOOTSTRAP} >> ${JS_BOOTSTRAP_MIN}
	@echo "Compiling and minifying bootstrap.js...       ${CHECK} Done"

build_plugins_js:
# ensure output dirs exist
	@mkdir -p ${ASSETS_ROOT}/js
	@echo "Ensuring JS asset output dirs exist...        ${CHECK} Done"
# builds 'plugin' js files
	@cat ${SRC_ROOT}/js/plugins/*.js > ${JS_PLUGINS}
	@uglifyjs -nc ${JS_PLUGINS} > ${JS_PLUGINS_MIN}
	@echo "Compiling and minifying JS 'plugins'...       ${CHECK} Done"

build_boot_js:
	@mkdir -p ${ASSETS_ROOT}/js
	@echo "Ensuring JS asset output dirs exist...        ${CHECK} Done"
	@cat ${SRC_ROOT}/js/boot.js > ${JS_BOOT}
	@uglifyjs -nc ${JS_BOOT} > ${JS_BOOT_MIN}
	@echo "Compiling and minifying JS 'bootfile'...      ${CHECK} Done"


build_js: build_libs_js build_plugins_js build_boot_js

#
# manage local webserver
#
start_svr:
	@if [ ! -f "${SVR_PIDFILE}" ]; then \
		node 'build/server.js' & \
		echo "Server starting on localhost:3000...       ${CHECK} Done" ; \
		open 'http://localhost:3000/' ; \
	else \
		echo "Server already running (pid `cat "${SVR_PIDFILE}"`)" ; \
	fi

stop_svr:
	@if [ -f "${SVR_PIDFILE}" ]; then \
		kill -9 `cat "${SVR_PIDFILE}"` ; \
		rm "${SVR_PIDFILE}" ; \
		echo "Server stopping...                         ${CHECK} Done" ; \
	fi

#
# WATCH LESS & JS FILES
#

watch:
	@echo "Watching Less & JS files (building for ${BUILD_ENV}) ..."; \
	BUILD_ENV=${BUILD_ENV} watchr build/build.watchr

.PHONY: watch jslint test build_start build_end build_css build_js build_libs_js build_plugins_js build_boot_js start_svr stop_svr