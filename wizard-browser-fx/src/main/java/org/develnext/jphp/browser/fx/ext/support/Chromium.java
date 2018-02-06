package org.develnext.jphp.browser.fx.ext.support;

import org.cef.browser.CefBrowser;

import javax.media.opengl.awt.GLJPanel;

public class Chromium extends SwingNodeFixed {
    private final CefBrowser engine;

    public Chromium(CefBrowser browser) {
        setContent((GLJPanel) browser.getUIComponent());
        this.engine = browser;
    }

    public CefBrowser getEngine() {
        return engine;
    }
}
