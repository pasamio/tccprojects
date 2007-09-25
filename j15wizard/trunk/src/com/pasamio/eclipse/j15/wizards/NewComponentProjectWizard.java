package com.pasamio.eclipse.j15.wizards;

import java.io.IOException;
import java.io.InputStream;
import java.lang.reflect.InvocationTargetException;
import java.net.URI;

import org.eclipse.core.resources.IContainer;
import org.eclipse.core.resources.IFile;
import org.eclipse.core.resources.IFolder;
import org.eclipse.core.resources.IProject;
import org.eclipse.core.resources.IProjectDescription;
import org.eclipse.core.resources.IResource;
import org.eclipse.core.resources.IWorkspace;
import org.eclipse.core.resources.ResourcesPlugin;
import org.eclipse.core.runtime.CoreException;
import org.eclipse.core.runtime.IConfigurationElement;
import org.eclipse.core.runtime.IExecutableExtension;
import org.eclipse.core.runtime.IProgressMonitor;
import org.eclipse.core.runtime.IStatus;
import org.eclipse.core.runtime.OperationCanceledException;
import org.eclipse.core.runtime.Path;
import org.eclipse.core.runtime.Status;
import org.eclipse.core.runtime.SubProgressMonitor;
import org.eclipse.jface.dialogs.MessageDialog;
import org.eclipse.jface.viewers.IStructuredSelection;
import org.eclipse.jface.wizard.Wizard;
import org.eclipse.ui.INewWizard;
import org.eclipse.ui.IWorkbench;
import org.eclipse.ui.actions.WorkspaceModifyOperation;
import org.eclipse.ui.dialogs.WizardNewProjectCreationPage;
import org.eclipse.ui.wizards.newresource.BasicNewProjectResourceWizard;

public class NewComponentProjectWizard extends Wizard implements INewWizard,
IExecutableExtension {

	/*
	 * Use the WizardNewProjectCreationPage, which is provided by the Eclipse
	 * framework.
	 */
	private WizardNewProjectCreationPage wizardPage;

	private IConfigurationElement config;

	private IWorkbench workbench;

	private IStructuredSelection selection;

	private IProject project;

	/**
	 * Constructor
	 */
	public NewComponentProjectWizard() {
		super();
	}

	public void addPages() {
		/*
		 * Unlike the custom new wizard, we just add the pre-defined one and
		 * don't necessarily define our own.
		 */
		wizardPage = new WizardNewProjectCreationPage(
		"NewExampleComSiteProject");
		wizardPage.setDescription("Create a new Example.com Site Project.");
		wizardPage.setTitle("New Example.com Site Project");
		addPage(wizardPage);
	}

	// @Override
	public boolean performFinish() {
		if (project != null) {
			return true;
		}

		final IProject projectHandle = wizardPage.getProjectHandle();

		URI projectURI = (!wizardPage.useDefaults()) ? wizardPage
				.getLocationURI() : null;

				IWorkspace workspace = ResourcesPlugin.getWorkspace();

				final IProjectDescription desc = workspace
				.newProjectDescription(projectHandle.getName());

				desc.setLocationURI(projectURI);

				/*
				 * Just like the ExampleWizard, but this time with an operation object
				 * that modifies workspaces.
				 */
				WorkspaceModifyOperation op = new WorkspaceModifyOperation() {
					protected void execute(IProgressMonitor monitor)
					throws CoreException {
						createProject(desc, projectHandle, monitor);
					}
				};

				/*
				 * This isn't as robust as the code in the BasicNewProjectResourceWizard
				 * class. Consider beefing this up to improve error handling.
				 */
				try {
					getContainer().run(true, true, op);
				} catch (InterruptedException e) {
					return false;
				} catch (InvocationTargetException e) {
					Throwable realException = e.getTargetException();
					MessageDialog.openError(getShell(), "Error", realException
							.getMessage());
					return false;
				}

				project = projectHandle;

				if (project == null) {
					return false;
				}

				BasicNewProjectResourceWizard.updatePerspective(config);
				BasicNewProjectResourceWizard.selectAndReveal(project, workbench
						.getActiveWorkbenchWindow());

				return true;
	}

	/**
	 * This creates the project in the workspace.
	 * 
	 * @param description
	 * @param projectHandle
	 * @param monitor
	 * @throws CoreException
	 * @throws OperationCanceledException
	 */
	void createProject(IProjectDescription description, IProject proj,
			IProgressMonitor monitor) throws CoreException,
			OperationCanceledException {
		try {

			monitor.beginTask("", 2000);

			proj.create(description, new SubProgressMonitor(monitor, 1000));

			if (monitor.isCanceled()) {
				throw new OperationCanceledException();
			}

			proj.open(IResource.BACKGROUND_REFRESH, new SubProgressMonitor(
					monitor, 1000));

			/*
			 * Okay, now we have the project and we can do more things with it
			 * before updating the perspective.
			 */
			IContainer container = (IContainer) proj;

			/* Add an XHTML file */
			/*addFileToProject(container, new Path("index.html"),
					J15NewModel.openContentStream("Welcome to "
							+ proj.getName(),"5"),monitor);*/

			/* Create the admin folder */
			final IFolder adminFolder = container.getFolder(new Path("admin"));
			adminFolder.create(true, true, monitor);
			
			final IFolder adminModels = adminFolder.getFolder(new Path("models"));
			adminModels.create(true, true, monitor);
			
			final IFolder adminControllers = adminFolder.getFolder(new Path("controllers"));
			adminControllers.create(true, true, monitor);
			
			final IFolder adminViews = adminFolder.getFolder(new Path("views"));
			adminViews.create(true, true, monitor);
			
			final IFolder adminTables = adminFolder.getFolder(new Path("tables"));
			adminTables.create(true, true, monitor);
			
			/* Create the site folder */
			final IFolder siteFolder = container.getFolder(new Path("site"));
			siteFolder.create(true, true, monitor);
			
			final IFolder siteModels = siteFolder.getFolder(new Path("models"));
			siteModels.create(true, true, monitor);
			
			final IFolder siteViews = siteFolder.getFolder(new Path("views"));
			siteViews.create(true, true, monitor);

			InputStream resourceStream = this.getClass().getResourceAsStream(
					"templates/blank-html.template");

			/* Add blank HTML Files */
			
			/* Admin Folders first */
			addFileToProject(container, new Path(adminFolder.getName()
					+ Path.SEPARATOR + "index.html"),
					resourceStream, monitor);
			
			resourceStream.close();
			resourceStream = this.getClass().getResourceAsStream(
				"templates/blank-html.template");
			
			addFileToProject(container, new Path(adminFolder.getName()
					+ Path.SEPARATOR + adminModels.getName()
					+ Path.SEPARATOR + "index.html"),
					resourceStream, monitor);
			
			resourceStream.close();
			resourceStream = this.getClass().getResourceAsStream(
				"templates/blank-html.template");
			
			addFileToProject(container, new Path(adminFolder.getName()
					+ Path.SEPARATOR + adminControllers.getName()
					+ Path.SEPARATOR + "index.html"),
					resourceStream, monitor);
			
			resourceStream.close();
			resourceStream = this.getClass().getResourceAsStream(
				"templates/blank-html.template");
			
			addFileToProject(container, new Path(adminFolder.getName()
					+ Path.SEPARATOR + adminViews.getName()
					+ Path.SEPARATOR + "index.html"),
					resourceStream, monitor);
			
			resourceStream.close();
			resourceStream = this.getClass().getResourceAsStream(
				"templates/blank-html.template");
			
			addFileToProject(container, new Path(adminFolder.getName()
					+ Path.SEPARATOR + adminTables.getName()
					+ Path.SEPARATOR + "index.html"),
					resourceStream, monitor);
			
			resourceStream.close();
			resourceStream = this.getClass().getResourceAsStream(
				"templates/blank-html.template");
			
			/* Now the site folders */
			addFileToProject(container, new Path(siteFolder.getName()
					+ Path.SEPARATOR + "index.html"),
					resourceStream, monitor);
			
			resourceStream.close();
			resourceStream = this.getClass().getResourceAsStream(
				"templates/blank-html.template");
			
			addFileToProject(container, new Path(siteFolder.getName()
					+ Path.SEPARATOR + siteModels.getName()
					+ Path.SEPARATOR + "index.html"),
					resourceStream, monitor);
			
			resourceStream.close();
			resourceStream = this.getClass().getResourceAsStream(
				"templates/blank-html.template");

			addFileToProject(container, new Path(siteFolder.getName()
					+ Path.SEPARATOR + siteViews.getName()
					+ Path.SEPARATOR + "index.html"),
					resourceStream, monitor);
			
			resourceStream.close();

			/* All over! */
		} catch (IOException ioe) {
			IStatus status = new Status(IStatus.ERROR, "J15Wizard", IStatus.OK,
					ioe.getLocalizedMessage(), null);
			throw new CoreException(status);
		} finally {
			monitor.done();
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.eclipse.ui.IWorkbenchWizard#init(org.eclipse.ui.IWorkbench,
	 *      org.eclipse.jface.viewers.IStructuredSelection)
	 */
	public void init(IWorkbench workbench, IStructuredSelection selection) {
        this.selection = selection;
        selection = this.selection;
        this.workbench = workbench;
	}

	/**
	 * Sets the initialization data for the wizard.
	 */
	public void setInitializationData(IConfigurationElement config,
			String propertyName, Object data) throws CoreException {
		this.config = config;
	}

	/**
	 * Adds a new file to the project.
	 * 
	 * @param container
	 * @param path
	 * @param contentStream
	 * @param monitor
	 * @throws CoreException
	 */
    private void addFileToProject(IContainer container, Path path,
            InputStream contentStream, IProgressMonitor monitor)
            throws CoreException {
        final IFile file = container.getFile(path);

        if (file.exists()) {
            file.setContents(contentStream, true, true, monitor);
        } else {
            file.create(contentStream, true, monitor);
        }

    }
}