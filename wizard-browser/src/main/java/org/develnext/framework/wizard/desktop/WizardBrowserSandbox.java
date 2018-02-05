package org.develnext.framework.wizard.desktop;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

public class WizardBrowserSandbox {
    public static void main(String[] args) {
        WizardBrowser browser = new WizardBrowser();
        browser.addWindowListener(new WindowAdapter() {
            @Override
            public void windowOpened(WindowEvent e) {
                browser.loadURL("http://bash.im");
            }
        });
    }
}
